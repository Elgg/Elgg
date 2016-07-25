<?php

$subtypes = [
	ElggDiscussion::SUBTYPE => ElggDiscussion::class,
	ElggDiscussionReply::SUBTYPE => ElggDiscussionReply::class,
];

foreach ($subtypes as $subtype => $class_name) {
	$old_class_name = get_subtype_class('object', $subtype);
	if ($old_class_name  == $class_name) {
		// Plugin classes will no longer be loaded once the plugin is deactivated,
		// so we need to make sure objects are no longer instantiated using them
		update_subtype('object', $subtype);
	}
}