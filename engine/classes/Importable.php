<?php
/**
 * Define an interface for all ODD importable objects.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Importable
 * @deprecated 1.9
 */
interface Importable {
	/**
	 * Accepts an array of data to import, this data is parsed from the XML produced by export.
	 * The function should return the constructed object data, or null.
	 *
	 * @param ODD $data Data in ODD format
	 *
	 * @return bool
	 * @throws ImportException if there was a critical error importing data.
	 */
	public function import(ODD $data);
}
