<?php
namespace Database\SQLFactory;

use Database\Query\Query,
	Database\Expr\ExprInterface;

class MySQLFactory implements SQLFactoryInterface
{
	public function generateDeleteSQL(Query $query) {
		
	}

	public function generateInsertSQL(Query $query) {
		
	}

	public function generateSelectSQL(Query $query) 
	{
		$whereClause = $this->_generateWhereClause($query);
		$parts = [
			"SELECT * FROM {$query->tableName()}",
			$whereClause
		];
			
		return trim(implode(" ", $parts));
	}

	public function generateUpdateSQL(Query $query) {
		
	}
	
	/**
	 * Generate a JOIN clause
	 * 
	 * @param string $tableName
	 * @param string|ExprInterface $expr
	 * @param string $type
	 * @return string
	 */
	public function generateJoinClause($tableName, $expr, $type = Query::JOIN_DEFAULT) 
	{
		if ($expr instanceof ExprInterface) {
			$expr = $expr->toString();
		}
		
		switch ($type) {
			case Query::JOIN_LEFT:
				return "LEFT JOIN {$tableName} {$expr}";
			case Query::JOIN_DEFAULT:
			default:
				return "JOIN {$tableName} {$expr}";
		}
	}
	
	/**
	 * Generate the WHERE portion of a SQL statement
	 * 
	 * @param array $exprs
	 * @return string
	 */
	public function generateWhereClause(array $exprs) 
	{
		return "WHERE ". implode(" AND ", $exprs);
	}
	
	/**
	 * Generate the ORDER clause of a SQL statement
	 * 
	 * @param array $exprs
	 * @return string
	 */
	public function generateOrderClause(array $exprs) 
	{
		return count($exprs) ? "ORDER BY ". implode(", ", $exprs) : null;
	}

}