<?php

interface ColumnRef {
	/**
	 * @param mixed $value A value expression (column reference, string, integer, or transformation)
	 * 
	 * @return WhereExpression
	 */
	public function equals($value);
}