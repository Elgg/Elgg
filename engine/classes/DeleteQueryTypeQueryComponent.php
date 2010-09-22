<?php
/**
 * Delete query
 *
 * @todo probably remove.
 * @access private
 * @package Elgg.Core
 * @subpackage Unimplemented
 */
class DeleteQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "DELETE FROM";
	}
}
