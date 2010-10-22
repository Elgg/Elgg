<?php
/**
 * @class InsertQueryTypeQueryComponent
 * An insert query.
 * @see Query
 */
class InsertQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "INSERT INTO";
	}
}
