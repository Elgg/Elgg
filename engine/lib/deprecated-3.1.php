<?php

/**
 * Delete a directory and all its contents
 *
 * @param string $directory Directory to delete
 *
 * @return bool
 *
 * @deprecated
 */
function delete_directory($directory) {
	
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_delete_directory().', '3.1');
	
	if (!is_string($directory)) {
		return false;
	}
	
	return elgg_delete_directory($directory);
}
