<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
interface Elgg_Database_Result extends
	Countable,
	Iterator
{

	/**
	 * Get affected rows
	 *
	 * @return int
	 */
	public function getAffectedRows();

	/**
	 * Get generated value
	 *
	 * @return int|null
	 */
	public function getGeneratedValue();

	/**
	 * Get the resource
	 *
	 * @return mixed
	 */
	public function getResource();

}
