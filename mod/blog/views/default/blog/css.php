<?php
/**
 * Blog CSS
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
*/
?>
.blogpost {
	border-bottom:1px dotted #CCCCCC;
}
.blogpost .entity_listing_icon {
	margin-top:6px;
}
.blogpost .entity_listing_info {
	margin-top:4px;
}
.blogpost .body {
	margin-top:5px;
	margin-bottom:10px;
	display:block;
}
.blogpost .body p {
	line-height: 1.4em;
	padding-bottom:12px;
}
form#blog_post_edit .submit_button {
	margin-right:15px;
}
/* force tinymce input height for a more useful editing / blog creation area */
form#blog_post_edit #description_parent #description_ifr {
	height:400px !important;
}