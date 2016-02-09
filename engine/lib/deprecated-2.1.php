<?php

/**
 * Convert a database row to a new \ElggRelationship
 *
 * @param \stdClass $row Database row from the relationship table
 *
 * @return \ElggRelationship|false
 * @access private
 */
function row_to_elggrelationship($row) {
	elgg_deprecated_notice(__FUNCTION__ . " is deprecated.", 2.1);
	return _elgg_services()->relationshipsTable->rowToElggRelationship($row);
}
