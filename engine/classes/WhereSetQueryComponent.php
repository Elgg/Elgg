<?php
/**
 * @class WhereSetQueryComponent
 * A where query that may contain other where queries (in brackets).
 * @see Query
 */
class WhereSetQueryComponent extends WhereQueryComponent
{
	/**
	 * Construct a subset of wheres.
	 *
	 * @param array $wheres An array of WhereQueryComponent
	 * @param string $link_operator How this where clause links with the previous clause, eg. "and" "or"
	 */
	function __construct(array $wheres, $link_operator = "and")
	{
		$this->link_operator = sanitise_string($link_operator);
		$this->wheres = $wheres;
	}

	public function toStringNoLink()
	{
		$cnt = 0;
		$string = " (";
		foreach ($this->wheres as $where) {
			if (!($where instanceof WhereQueryComponent))
				throw new DatabaseException(elgg_echo('DatabaseException:WhereSetNonQuery'));

			if (!$cnt)
				$string.= $where->toStringNoLink();
			else
				$string.=" $where ";

			$cnt ++;
		}
		$string .= ")";

		return $string;
	}
}
