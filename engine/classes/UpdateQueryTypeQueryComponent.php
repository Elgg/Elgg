<?php
/**
 * @class UpdateQueryTypeQueryComponent
 * An update query.
 * @see Query
 */
class UpdateQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "UPDATE";
	}
}
