<?php
/**
 * @class QueryTypeQueryComponent
 * What type of query is this?
 * @see Query
 */
abstract class QueryTypeQueryComponent extends QueryComponent
{
	function __toString()
	{
		return $this->query_type;
	}
}
