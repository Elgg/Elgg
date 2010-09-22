<?php
/**
 * @class InsertQueryTypeQueryComponent
 * An insert query.
 * @author Curverider Ltd
 * @see Query
 */
class InsertQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "INSERT INTO";
	}
}
