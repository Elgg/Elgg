<?php
/**
 * Register thewire objects with the ElggWire class.
 */

if (get_subtype_id('object', 'thewire')) {
	update_subtype('object', 'thewire', 'ElggWire');
}