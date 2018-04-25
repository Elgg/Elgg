<?php

namespace Elgg\Markup;

class TableHead extends Tag {

	protected $tag_name = 'thead';

	public function __construct($children = null, array $attributes = [], array $options = []) {
		parent::__construct($children, $attributes, $options);

		$this->row = new TableRow();

		$this->append($this->row);
	}

	/**
	 * Clear all headings
	 * @return static
	 */
	public function clearHeadings() {
		$this->row->clear();

		return $this;
	}

	/**
	 * Add heading
	 *
	 * @param TableHeading[]|TableHeading|Element|string ...$headings Headings
	 *
	 * @return static
	 */
	public function addHeadings(...$headings) {
		$headings = $this->flatten($headings);

		foreach ($headings as $th) {
			if (!$th instanceof TableHeading) {
				$th = new TableHeading($th);
			}

			$this->row->append($th);
		}

		return $this;
	}

	/**
	 * Get headings
	 * @return Element[]
	 */
	public function getHeadings() {
		return $this->row->getChildren();
	}
}