<?php
namespace Elgg\Views\TableColumn;

use Elgg\Views\TableColumn;

/**
 * Table column rendered by a function
 */
class CallableColumn implements TableColumn {

	/**
	 * Constructor
	 *
	 * @param callable $renderer Rendering function
	 * @param string   $heading  Heading
	 */
	public function __construct(protected callable $renderer, protected $heading) {
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderHeading() {
		return $this->heading;
	}

	/**
	 * {@inheritdoc}
	 */
	public function renderCell($item, $type, $item_vars) {
		return call_user_func($this->renderer, $item, $type, $item_vars);
	}
}
