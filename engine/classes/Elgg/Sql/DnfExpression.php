<?php
namespace Elgg\Sql;

/**
 * DNF stands for Disjunctive Normal Form: http://en.wikipedia.org/wiki/Disjunctive_normal_form
 * 
 * Chaining ANDs and ORs from this expression will append them as if they were
 * written without parentheses grouping the expressions, and assuming ANDs are
 * higher priority than ORs.
 * 
 * For example:
 * 
 * $this->and($foo)->and($bar)->or($baz)->and($blop)
 * 
 * Will evaluate to an expression like (parens added for clarity):
 * 
 * $this AND ($foo) AND ($bar) OR ($baz) AND ($bop)
 * 
 * Which is the same as
 * 
 * ($this AND $foo AND $bar) OR ($baz AND $bop);
 * 
 * Which is already in DNF.
 * 
 * If you instead wanted something like:
 * 
 * $this AND $foo AND ($bar OR $baz) AND $bop;
 * 
 * Then the query should be built like so:
 * 
 * $this->and($foo)->and($bar->or($baz))->and($bop);
 * 
 * The parens in PHP closely resemble the parens that end up in the SQL clause,
 * so this should be a relatively easy transformation to understand and remember.
 * 
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @package    Elgg.Core
 * @subpackage Sql
 * @since      1.11
 * 
 * @access private
 */
class DnfExpression implements WhereExpression {
	private $oredExpressions = array();
	
	private $andedExpressions = array();
	
	/**
	 * Constructor
	 * 
	 * @param WhereExpression $expr Initial expression (e.g. ComparisonExpression)
	 */
	public function __construct(WhereExpression $expr) {
		$this->andedExpressions[] = $expr;
	}
	
	/**
	 * @inheritDoc
	 * @return DnfExpression
	 */
	public function and(WhereExpression $expr) {
		$this->andedExpressions[] = $expr;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return DnfExpression
	 */
	public function or(WhereExpression $expr) {
		$this->oredExpressions[] = $this->andedExpressions;
		$this->andedExpressions = array($expr);
		return $this;
	}
}