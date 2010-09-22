<?php
/**
 * @class UpdateQueryTypeQueryComponent
 * An update query.
 * @author Curverider Ltd
 * @see Query
 */
class UpdateQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "UPDATE";
	}
}
