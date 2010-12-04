<?php
/**
 * Blog CSS
 *
 * @package Blog
*/
?>
.blogpost .entity-listing-icon {
	margin-top:6px;
}
.blogpost .entity-listing-info {
	margin-top:4px;
}
.blog_post {
	border-top:1px solid #CCCCCC;
	border-bottom:1px dotted #CCCCCC;
	margin:10px 0 0;
	padding-bottom:40px;
	padding-top:10px;
}
.blog_post p {
	line-height: 1.4em;
	padding-bottom:12px;
}
.blog_archives {
	list-style: none;
	margin-bottom:0;
	padding-left:0;
}

/* blogs list view */
.entity-listing.blog .entity-metadata {
	min-width:400px;
	text-align: right;
}

/* blogs edit/create form */
form#blog_post_edit .submit-button {
	margin-right:15px;
}
/* force tinymce input height for a more useful editing / blog creation area */
form#blog_post_edit #description_parent #description_ifr {
	height:400px !important;
}