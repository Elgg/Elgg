<?php

namespace Elgg\Views\TableColumn;

use Elgg\Views\TableColumn;

/**
 * Table column rendered by a view
 */
class ViewColumn implements TableColumn {

	protected string $heading;

	/**
	 * Constructor
	 *
	 * @param string $view    The view to render the value
	 * @param string $heading Heading
	 * @param array  $vars    Vars to merge into the view vars
	 */
	public function __construct(protected string $view, string $heading = null, protected array $vars = []) {
		if (!is_string($heading)) {
			$heading = elgg_echo("ViewColumn:view:{$view}");
		}
		
		$this->heading = $heading;
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
		$vars = array_merge($this->vars, [
			'item' => $item,
			'item_vars' => $item_vars,
			'type' => $type,
		]);

		return elgg_view($this->view, $vars);
	}
}
