<?php
/**
 * Elgg 1.9.0-dev upgrade 2014031100
 * elgg_upgrade_object
 *
 * Registers an ElggUpgrade object.
 */

if (get_subtype_id('object', 'elgg_upgrade')) {
	update_subtype('object', 'elgg_upgrade', 'ElggUpgrade');
} else {
	add_subtype('object', 'elgg_upgrade', 'ElggUpgrade');
}
