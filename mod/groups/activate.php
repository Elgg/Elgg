<?php
/**
 * Register the ElggDiscussionReply class for the object/discussion_reply subtype
 */

if (get_subtype_id('object', 'discussion_reply')) {
	update_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
} else {
	add_subtype('object', 'discussion_reply', 'ElggDiscussionReply');
}
