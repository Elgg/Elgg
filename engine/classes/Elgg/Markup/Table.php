<?php

namespace Elgg\Markup;

/**
 * <table> element
 */
class Table extends Tag {

	protected $tag_name = 'table';

	/**
	 * @var TableHead
	 */
	protected $head;

	/**
	 * @var TableBody
	 */
	protected $body;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($children = null, array $attributes = []) {
		parent::__construct($children, $attributes);

		$this->head = new TableHead();
		$this->body = new TableBody();

		$this->append($this->head);
		$this->append($this->body);
	}

	/**
	 * Set table headings
	 *
	 * @param TableHeading[]|TableHeading|string ...$headings Headings
	 *
	 * @return static
	 */
	public function setHeadings(...$headings) {
		$this->head->clearHeadings();
		$this->head->addHeadings($headings);

		return $this;
	}

	/**
	 * Add a table row
	 *
	 * @param TableRow|TableCell[]|Element[]|string[] $row Row
	 *
	 * @return static
	 */
	public function addRow($row) {
		if ($row instanceof TableRow) {
			$this->body->append($row);
		} else {
			if (!is_array($row)) {
				$row = [$row];
			}

			$diff = sizeof($this->head->getHeadings()) - sizeof($row);
			if ($diff > 0) {
				$row = array_pad($row, $diff, '');
			}

			$row = array_map(function ($e) {
				if ($e instanceof TableCell) {
					return $e;
				}

				return new TableCell($e);
			}, $row);

			$this->body->append(new TableRow($row));
		}

		return $this;
	}

}