<?php
	/**
	 * File CSS extender 
	 * 
	 * @package Elgg File Repository
	 */
?>
.files .entity-listing .entity-listing-info {
	width:453px;
}
.files .entity-listing:hover {
	background-color: white;
}

/* files - single entity view */
.filerepo_title_owner_wrapper .filerepo_title,
.filerepo_title_owner_wrapper .filerepo_owner,
.filerepo_file .filerepo_maincontent {
	margin-left: 70px !important;
}
.filerepo_owner_details {
	margin:0;
	padding:0;
	line-height: 1.2em;
}
.filerepo_owner_details small {
	color:#666666;
}
.filerepo_owner .usericon {
	margin: 3px 5px 5px 0;
	float: left;
}
.filerepo_file .filerepo_icon {
	width: 70px;
	position: absolute;
	margin:5px 0 10px 0;
}
.filerepo_file .filerepo_title {
	margin:0;
	padding:7px 4px 10px 0;
	line-height: 1.2em;
}
.filerepo_file .filerepo_description {
	margin:10px 0 0 0;
}
.filerepo_file .filerepo_description p {
	padding:0 0 5px 0;
	margin:0;
}
.filerepo_file .filerepo_specialcontent img {
	padding:10px;
	margin-bottom:10px;
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
	background: #333333; 
}


/* files - gallery view */
.entity_gallery_item .filerepo_gallery_item {
	margin:10px 10px 0 0;
	padding:5px;
	text-align:center;
	background-color: #eeeeee;
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
	width:165px;
}
.entity_gallery_item .filerepo_gallery_item:hover {
	background-color: #999999;
}
.filerepo_download,
.filerepo_controls {
	padding:0 0 1px 0;
	margin:0 0 10px 0;
}
.entity_gallery .filerepo_title {
	font-weight: bold;
	line-height: 1.1em;
	margin:0 0 10px 0;
}
.filerepo_gallery_item p {
	margin:0;
	padding:0;
}
.filerepo_gallery_item .filerepo_controls {
	margin-top:10px;
}
.filerepo_gallery_item .filerepo_controls a {
	padding-right:10px;
	padding-left:10px;
}
.entity_gallery .filerepo_comments {
	font-size:90%;
}
.filerepo_user_gallery_link {
	float:right;
	margin:5px 5px 5px 50px;
}
.filerepo_user_gallery_link a {
	padding:2px 25px 5px 0;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/file/graphics/icon_gallery.gif) no-repeat right top;
	display:block;
}
.filerepo_user_gallery_link a:hover {
	background-position: right -40px;
}






