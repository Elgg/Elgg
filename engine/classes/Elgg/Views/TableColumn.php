<?php
namespace Elgg\Views;

/**
 * A renderer for a column of table cells and a header
 */
interface TableColumn {

	/**
	 * Get the rendered heading cell as HTML. Cell will be auto-wrapped with a TH element if the
	 * returned string doesn't begin with "<th" or "<td".
	 *
	 * @return string e.g. "Title" or "<th>Title</th>". You must filter/escape any user content.
	 */
	public function renderHeading();

	/**
	 * Render a value cell as HTML. Cell will be auto-wrapped with a TD element if the returned
	 * string doesn't begin with "<th" or "<td".
	 *
	 * @param mixed  $item      Object/row from which to pull the value
	 * @param string $type      Type of object
	 * @param array  $item_vars Parameters from the listing function
	 *
	 * @return string e.g. "My Great Title" or "<td>My Great Title</td>". You must filter/escape any user content.
	 */
	public function renderCell($item, $type, $item_vars);
}
