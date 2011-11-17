<?php
/**
 * Register the ElggWire class for the object/thewire subtype
 */

if (get_subtype_id('object', 'thewire')) {
	update_subtype('object', 'thewire', 'ElggWire');
} else {
	add_subtype('object', 'thewire', 'ElggWire');
}
