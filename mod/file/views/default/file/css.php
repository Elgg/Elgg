<?php
	/**
	 * File CSS extender 
	 * 
	 * @package Elgg File Repository
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
?>


p.filerepo_owner {
	margin:0;
	padding:0;
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

.filerepo_download a {
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: white;
	background:#4690d6;
	border:none;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
	width: auto;
	height: 25px;
	padding: 3px 6px 3px 6px;
	margin:10px 0 10px 0;
	cursor: pointer;
}
.filerepo_download a:hover {
	background: black;
	color:white;
	text-decoration: none;
}

/* FILE REPRO WIDGET VIEW */
.filerepo_widget_singleitem {
	margin:0 0 5px 0;
	padding:5px;
	min-height:60px;
	display:block;
	background:white;
   	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
.filerepo_widget_singleitem_more {
	margin:0;
	padding:5px;
	display:block;
	background:white;
   	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;	
}
.filerepo_listview_icon {
	float: left;
	margin-right: 10px;
}
.filerepo_timestamp {
	color:#666666;
	margin:0;
}
.filerepo_listview_desc {
	display:none;
	padding:0 10px 10px 0;
	line-height: 1.2em;
}
.filerepo_listview_desc p {
	color:#333333;
}
.filerepo_widget_content {
	margin-left: 70px;
}
.filerepo_title {
	margin:0;
	padding:6px 5px 0 0;
	line-height: 1.2em;
	color:#666666;
	font-weight: bold;
}

.collapsable_box #filerepo_widget_layout {
	margin:0 10px 0 10px;
	background: none;
}

/* widget gallery view */
#filerepo_widget_layout .filerepo_widget_galleryview {
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background: white;
	margin:0 0 5px 0;	
}
.filerepo_widget_galleryview img {
	padding:0;
    border:1px solid white;
    margin:4px;
}
.filerepo_widget_galleryview img:hover {
	border:1px solid #333333;
}

/* SINGLE ITEM VIEW */
.filerepo_file {
	background:white;
	margin:10px 10px 0 10px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
.filerepo_file .filerepo_title_owner_wrapper {
	min-height:60px;

}
.filerepo_title_owner_wrapper .filerepo_title,
.filerepo_title_owner_wrapper .filerepo_owner {
	margin-left: 70px !important;
}
.filerepo_file .filerepo_maincontent {
	padding:0 20px 0 0;
}
.filerepo_file .filerepo_icon {
	width: 70px;
	position: absolute;
	margin:10px 0 10px 10px;
}
.filerepo_file .filerepo_title {
	margin:0;
	padding:7px 4px 10px 10px;
	line-height: 1.2em;
}
.filerepo_file .filerepo_owner {
	padding:0 0 0 10px;
}
.filerepo_file .filerepo_description {
	margin:10px 0 0 0;
	padding:0 0 0 10px;
}
.filerepo_download,
.filerepo_controls {
	padding:0 0 1px 10px;
	margin:0 0 10px 0;
}
.filerepo_file .filerepo_description p {
	padding:0 0 5px 0;
	margin:0;
}
.filerepo_file .filerepo_specialcontent img {
	padding:10px;
	margin:0 0 0 10px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	background: black; 
}
/* zaudio player */
.filerepo_maincontent .filerepo_specialcontent {
	margin:0 0 20px 0;
}
.filerepo_tags {
	padding:0 0 10px 10px;
	margin:0;
}

/* file repro gallery items */
.search_gallery .filerepo_controls {
	padding:0;
}
.search_gallery .filerepo_title {
	font-weight: bold;
	line-height: 1.1em;
	margin:0 0 10px 0;
}
.filerepo_gallery_item {
	margin:0;
	padding:0;
	text-align:center;

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
.search_gallery .filerepo_comments {
	font-size:90%;
}

.filerepo_user_gallery_link {
	float:right;
	margin:5px 5px 5px 50px;
}
.filerepo_user_gallery_link a {
	padding:2px 25px 5px 0;
	background: transparent url(<?php echo $vars['url']; ?>_graphics/icon_gallery.gif) no-repeat right top;
	display:block;
}
.filerepo_user_gallery_link a:hover {
	background-position: right -40px;
}

/* IE6 */
* html #description_tbl { width:676px !important;}



