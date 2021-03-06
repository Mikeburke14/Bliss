<?php
namespace User\Controller;

use Bliss\Controller\AbstractController,
	User\DbTable as UserDbTable,
	User\Form\SignUpForm;

class AuthController extends AbstractController
{
	public function signInAction(\Request\Module $request, \User\Module $user)
	{
		if ($request->isPost()) {
			$email = $this->param("email");
			$password = $this->param("password");
			$remember = (boolean) $this->param("remember", 0);
			$manager = $user->sessionManager();
			$session = $manager->createSession($email, $password);
			
			if (!$session->isValid()) {
				throw new \Exception("Invalid credentials provided", 401);
			}
			
			$manager->attachUser($session);
			$manager->save($session);
			
			return $session->toArray();
		}
	}
	
	public function signUpAction(\Database\Module $db, \Request\Module $request, \User\Module $user)
	{
		if ($request->isPost()) {
			$form = new SignUpForm(
				new UserDbTable($db->connection())
			);
			$userData = $this->param("user", []);
			$user = $form->create($userData);
			
			if ($user === false) {
				return [
					"result" => "error",
					"errors" => $form->errors()
				];
			} else {
				$manager = $user->sessionManager();
				$session = $manager->createSession($user->email(), $userData["password"]);
				$manager->save($session);
				
				return $user->toArray();
			}
		}
	}
	
	public function signOutAction(\Request\Module $request, \User\Module $user) 
	{
		if ($request->isPost()) {
			$session = $user->session();
			$session->delete();
			
			return [
				"result" => "success"
			];
		}
	}
}