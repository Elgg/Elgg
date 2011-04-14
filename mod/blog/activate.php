<?php
/**
 * Blogs
 *
 * Activation file - runs when blog plugin is activated.
 *
 * @package Blog
 */

add_subtype('object', 'blog', 'ElggBlog');
