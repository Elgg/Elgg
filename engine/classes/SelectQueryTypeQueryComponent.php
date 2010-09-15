<?php
/**
 * @class SelectQueryTypeQueryComponent
 * A select query.
 * @author Curverider Ltd
 * @see Query
 */
class SelectQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "SELECT";
	}
}
