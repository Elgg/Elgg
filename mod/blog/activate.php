<?php
/**
 * Register the ElggBlog class for the object/blog subtype
 */

if (get_subtype_id('object', 'blog')) {
	update_subtype('object', 'blog', 'ElggBlog');
} else {
	add_subtype('object', 'blog', 'ElggBlog');
}
