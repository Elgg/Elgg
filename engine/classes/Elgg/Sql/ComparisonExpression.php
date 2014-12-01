<?php
namespace Elgg\Sql;

/**
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @package    Elgg.Core
 * @subpackage Sql
 * @since      1.11
 * 
 * @access private
 */
class Comparison implements WhereExpression {
	private $left;
	private $type;
	private $right;
	
	const EQUALS = 'equals';
	
	/**
	 * @param mixed  $left
	 * @param string $type
	 * @param mixed  $right
	 */
	public function __construct($left, $type, $right) {
		$this->left = $left;
		$this->type = $type;
		$this->right = $right;
	}
	
	/**
	 * @return mixed
	 */
	public function getLeft() {
		return $this->left;
	}
	
	/**
	 * @return mixed
	 */
	public function getRight() {
		return $this->right;
	}
	
	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/** @inheritDoc */
	public function and(WhereExpression $expr) {
		return new DnfExpression($this)->and($expr);
	}
	
	/** @inheritDoc */
	public function or(WhereExpression $expr) {
		return new DnfExpression($this)->or($expr);
	}
}