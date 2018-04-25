<?php

namespace Elgg\Markup;

/**
 * Markup element
 */
interface Element {

	/**
	 * Render an element
	 *
	 * @param array $options Options
	 *
	 * @return string
	 */
	public function render(array $options = []);

}