<?php

interface WhereExpression {
	/**
	 * @return WhereExpression
	 */
	public function or(WhereExpression $expr);
	
	/**
	 * @return WhereExpression
	 */
	public function and(WhereExpression $expr);
}