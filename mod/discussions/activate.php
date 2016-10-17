<?php

// Update subtype classes

$subtypes = [
	ElggDiscussion::SUBTYPE => ElggDiscussion::class,
	ElggDiscussionReply::SUBTYPE => ElggDiscussionReply::class,
];

foreach ($subtypes as $subtype => $class_name) {
	if (!get_subtype_id('object', $subtype)) {
		add_subtype('object', $subtype, $class_name);
		continue;
	}
	// We want to make sure we respect class declarations set by other plugins,
	// in case the plugin was deactivated accidentally
	$old_class_name = get_subtype_class('object', $subtype);
	if (!$old_class_name || $old_class_name == ElggObject::class) {
		update_subtype('object', $subtype, $class_name);
	}
}
