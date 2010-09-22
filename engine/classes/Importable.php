<?php
/**
 * Define an interface for all ODD importable objects.
 * @author Curverider Ltd
 */
interface Importable {
	/**
	 * Accepts an array of data to import, this data is parsed from the XML produced by export.
	 * The function should return the constructed object data, or NULL.
	 *
	 * @param ODD $data
	 * @return bool
	 * @throws ImportException if there was a critical error importing data.
	 */
	public function import(ODD $data);
}
