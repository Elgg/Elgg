<?php
/**
 * @class LimitOffsetQueryComponent
 * Limit and offset clauses of a query.
 * @author Curverider Ltd
 * @see Query
 */
class LimitOffsetQueryComponent extends QueryComponent
{
	/**
	 * Specify a limit and an offset.
	 *
	 * @param int $limit The limit.
	 * @param int $offset The offset.
	 */
	function __construct($limit = 25, $offset = 0)
	{
		$this->limit = (int)$limit;
		$this->offset = (int)$offset;
	}

	function __toString()
	{
		return "limit {$this->offset}, {$this->limit}";
	}
}
