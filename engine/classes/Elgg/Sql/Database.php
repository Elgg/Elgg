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
interface Database {
	/**
	 * @param string $name
	 * 
	 * @return Table
	 */
	public function getTable($name);
}