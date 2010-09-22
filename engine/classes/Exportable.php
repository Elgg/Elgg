<?php
/**
 * Define an interface for all ODD exportable objects.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 */
interface Exportable {
	/**
	 * This must take the contents of the object and convert it to exportable ODD
	 * @return object or array of objects.
	 */
	public function export();

	/**
	 * Return a list of all fields that can be exported.
	 * This should be used as the basis for the values returned by export()
	 */
	public function getExportableValues();
}