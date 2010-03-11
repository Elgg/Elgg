<?php

	/**
	 * Elgg blog css
	 * 
	 */

?>
#blogs .pagination {
/*
	margin:5px 10px 0 10px;
	padding:5px;
	display:block;
*/
}

.singleview {
	/* margin-top:10px !important; */
}

/*
we're partly using the #NewListStyle on blogs
ItemMetaData block only
*/ 
#blogs .ContentWrapper.Welcome {
	padding:10px 0 10px 0;
}
#blogs .search_listing .search_listing_info .ItemMetaData {
	float:right;
	margin-left:15px;
	margin-top:0;
	margin-right: 3px;
	color:#AAAAAA;
	text-align: right;
	font-size:90%;
}
#blogs .search_listing .search_listing_info .ItemMetaData table {
	width:200px;
	/* float:right; removed for ie7 compatability */
}
#blogs .search_listing .search_listing_info .ItemMetaData .EditItem a {
	color:#AAAAAA;
	margin:0 0 0 10px;
}
#blogs .search_listing .search_listing_info .ItemMetaData .EditItem a:hover {
	color: #0054a7;
	text-decoration: underline;
}

#blogs .search_listing .search_listing_info .ItemMetaData td.FavouriteItem {
	padding-top:0;
}
/* IE7 */
*:first-child+html #blogs .search_listing .search_listing_info .ItemMetaData td.FavouriteItem { width:auto; }


/* BLOG TITLE IN LIST VIEWS */
#blogs h2.blog_title {
	line-height:1.1em;
	margin-top:0;
	font-size:1.4em;
}
#blogs h2.blog_title a {
	color:#0054A7;
}
#blogs .search_listing_info p.blog_excerpt {
	margin-top:3px;
	padding-top:2px;
	border-top:1px solid #cccccc;
}
#blogs .search_listing_info p.owner_timestamp {
	margin-top:2px;
}

.Page_Header_Options .cancel_button {
	float:right;
	margin:0 10px 0 0;
}

.blog_post_icon {
	float:left;
	margin:0 0 0 0;
	padding:0;
}
#blogs .search_listing_info {
	margin-left:34px;
}

.blog_post #content_area_user_title {
	
}
.blog_post #content_area_user_title h2 {
	margin:0 0 5px;
	padding:0 0 5px;
	border-bottom:1px solid #cccccc;
}
.blog_post .strapline {
	margin: 0 0 0 30px;
	padding:0;
	color: #aaa;
	line-height:0.8em;
}
.blog_post .strapline .generic_access,
.blog_post .strapline .shared_collection,
.blog_post .strapline .group_open,
.blog_post .strapline .group_closed {
	line-height:1.4em;
	display:block;
}
.blog_post p.tags {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icon_tag.png) no-repeat scroll left 2px;
	margin:0;
	padding:0 0 0 16px;
	min-height:22px;
}
.blog_post .options {
	margin:0;
	padding:0;
}
.blog_post_body {
	margin-top:2px;
	padding-top:8px;
	border-top:1px solid #cccccc;
}
.blog_post_body img[align="left"] {
	margin: 10px 10px 10px 0;
	float:left;
}
.blog_post_body img[align="right"] {
	margin: 10px 0 10px 10px;
	float:right;
}
.blog_post_body img {
	margin: 10px !important;
}

.blog-comments h3 {
	font-size: 150%;
	margin-bottom: 10px;
}
.blog-comment {
	margin-top: 10px;
	margin-bottom:20px;
	border-bottom: 1px solid #aaaaaa;
}
.blog-comment img {
	float:left;
	margin: 0 10px 0 0;
}
.blog-comment-menu {
	margin:0;
}
.blog-comment-byline {
	background: #dddddd;
	height:22px;
	padding-top:3px;
	margin:0;
}
.blog-comment-text {
	margin:5px 0 5px 0;
}




/* unsaved blog post preview */
.blog_previewpane {
    border:1px solid #D3322A;
    background:#F7DAD8;
	padding:10px;
	margin:10px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;	
}
.blog_previewpane p {
	margin:0;
}

#blog_edit_page .publish_controls,
#blog_edit_page .blog_access,
#blog_edit_page .publish_options,
#blog_edit_page .publish_blog,
#blog_edit_page .allow_comments,
#blog_edit_page .categories {
	margin:0 5px 5px 5px;
	border-top:1px solid #cccccc;
}
#blog_edit_page ul {
	padding-left:0px;
	margin:5px 0 5px 0;
	list-style: none;
}
#blog_edit_page p {
	margin:5px 0 5px 0;
}
#blog_edit_page .publish_blog input[type="submit"] {
	font-weight: bold;
	padding:2px;
	height:auto;
}
#blog_edit_page .preview_button a {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	background:white;
	border: 1px solid #cccccc;
	color:#999999;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	width: auto;
	height: auto;
	padding: 3px;
	margin:1px 1px 5px 10px;
	cursor: pointer;
	float:right;
}
#blog_edit_page .preview_button a:hover {
	background:#4690D6;
	color:white;
	text-decoration: none;
	border: 1px solid #4690D6;
}
#blog_edit_page .allow_comments label {
	font-size: 100%;
}


/* blog edit page */
#blogPostForm .ContentWrapper {
	margin-top:10px;
}
#blogPostForm .ContentWrapper #excerpt_editarea {
	margin-top:15px;
	margin-bottom:15px;
}
#excerpt_editarea .input_textarea {
	height:80px;
}
#blogPostForm .current_access {
	color:inherit;
	font-size:inherit;
	line-height:1.0em;
	padding-top:0;
}

/* blog widget on groups */
.collapsable_box_content .ContentWrapper.blogs.more {
	margin:0 10px;
	padding:5px 10px;
}
.collapsable_box_content .ContentWrapper.blogs {
	line-height:1.2em;
	margin-bottom:5px;
}
.collapsable_box_content .ContentWrapper.blogs .river_object_blog_create {
	background-position:left 2px;
	min-height:17px;
	padding:2px 0 2px 19px;
	border-bottom:1px solid #DDDDDD;
	line-height:1.1em;
}
.collapsable_box_content .ContentWrapper.blogs .river_object_blog_create:first-child {
	border-top:1px solid #DDDDDD;
}
.collapsable_box_content .ContentWrapper.blogs .river_object_blog_create span {
	font-size: 90%;
	color:#666666;
}


