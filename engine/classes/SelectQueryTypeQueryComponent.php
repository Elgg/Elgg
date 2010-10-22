<?php
/**
 * @class SelectQueryTypeQueryComponent
 * A select query.
 * @see Query
 */
class SelectQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "SELECT";
	}
}
