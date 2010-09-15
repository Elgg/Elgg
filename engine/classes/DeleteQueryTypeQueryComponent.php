<?php
/**
 * @class DeleteQueryTypeQueryComponent
 * A delete query.
 * @author Curverider Ltd
 * @see Query
 */
class DeleteQueryTypeQueryComponent extends QueryTypeQueryComponent
{
	function __construct()
	{
		$this->query_type = "DELETE FROM";
	}
}
