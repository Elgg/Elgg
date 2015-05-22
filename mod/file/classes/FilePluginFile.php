<?php

/**
 * Removed subclass of ElggFile. See https://github.com/Elgg/Elgg/issues/7763
 */
class FilePluginFile {
	/**
	 * Constructor
	 *
	 * @throws APIException
	 */
	public function __construct() {
		throw new APIException(__CLASS__ . ' is no longer available. Use ElggFile or get_entity()');
	}
}
