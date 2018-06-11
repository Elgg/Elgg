<?php
/**
 * User view in User Picker
 *
 * @uses $vars['entity'] User entity
 * @uses $vars['input_name'] Name of the returned data array
 */

elgg_deprecated_notice("input/userpicker/item has been deprecated, please use input/autocomplete/item", '3.0');

echo elgg_view('input/autocomplete/item', $vars);
