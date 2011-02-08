<?php
/**
 * Define an interface for all ODD exportable objects.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 */
interface Exportable {
	/**
	 * This must take the contents of the object and convert it to exportable ODD
	 *
	 * @return object or array of objects.
	 */
	public function export();

	/**
	 * Return a list of all fields that can be exported.
	 * This should be used as the basis for the values returned by export()
	 *
	 * @return array
	 */
	public function getExportableValues();
}
