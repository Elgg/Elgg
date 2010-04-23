<?php
/**
 * Elgg embed CSS - standard across all themes
 * 
 * @package embed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */
?>

#facebox {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 10000;
	text-align: left;
}
#facebox .popup {
	position: relative;
}
#facebox .body {
	padding: 10px;
	background-color: white;
	width: 730px;
	-webkit-border-radius: 12px; 
	-moz-border-radius: 12px;
}
#facebox .loading {
	text-align: center;
	padding: 100px 10px 100px 10px;
}
#facebox .image {
	text-align: center;
}
#facebox .footer {
	float: right;
	width:22px;
	height:22px;
	margin:0;
	padding:0;
}
#facebox .footer img.close_image {
	background: url(<?php echo $vars['url']; ?>mod/embed/images/close_button.gif) no-repeat left top;
}
#facebox .footer img.close_image:hover {
	background: url(<?php echo $vars['url']; ?>mod/embed/images/close_button.gif) no-repeat left -31px;
}
#facebox_overlay {
	position: fixed;
	top: 0px;
	left: 0px;
	height:100%;
	width:100%;
}
.facebox_hide {
	z-index:-100;
}
.facebox_overlayBG {
	background-color: #000000;
	z-index: 9999;
}
* html #facebox_overlay { /* ie6 hack */
	position: absolute;
	height: expression(document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px');
}

/* entity listing overrides */
#media_upload,
#media_embed {
	margin:0 5px 10px 5px;
	padding:10px;
	border:2px solid #dedede;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background: #dedede;
}
#media_embed .entity_listing {
	margin:0;
	padding:0;
	background-color: white;
}
#media_embed .entity_listing_info {
	width:610px;
}
#media_upload .input_textarea {
	height:100px;
	width:682px;
}
#media_embed .embed_instructions {
	margin:10px 0 5px 0;
}

/* modal tabs */
#embed_media_tabs {
	margin:10px 0 0 10px;
	padding:0;
}
#embed_media_tabs ul {
	list-style: none;
	padding-left: 0;
}
#embed_media_tabs ul li {
	float: left;
	margin:0;
	background:white;
}
#embed_media_tabs ul li a {
	font-weight: bold;
	font-size:1.2em;
	text-align: center;
	text-decoration: none;
	color:#b6b6b6;
	background: white;
	display: block;
	padding: 3px 10px 0 10px;
	margin:0 10px 0 10px;
	height:20px;
	width:auto;
	border-top:2px solid #dedede;
	border-left:2px solid #dedede;
	border-right:2px solid #dedede;
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-topright: 8px;
	-webkit-border-top-left-radius: 8px;
	-webkit-border-top-right-radius: 8px;
}
#embed_media_tabs ul li a:hover {
	background:#b6b6b6;
	color:white;
	border-top:2px solid #b6b6b6;
	border-left:2px solid #b6b6b6;
	border-right:2px solid #b6b6b6;
}
#embed_media_tabs ul li a.embed_tab_selected {
	border-top:2px solid #dedede;
	border-left:2px solid #dedede;
	border-right:2px solid #dedede;
	-webkit-border-top-left-radius: 8px;
	-webkit-border-top-right-radius: 8px;
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-topright: 8px;
	background: #dedede;
	color:#666666;
	position: relative;
}
/* IE6 fix */
* html #embed_media_tabs ul li a { display: inline; }


/* Pagination (override core elgg css defaults) */
#media_embed .pagination,
#media_upload .pagination {
	float:right;
	padding:5px;
	background-color:white;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;	
}
#media_embed .pagination .pagination_number {
	border:1px solid #999999; 
	color:#666666;
}
#media_embed .pagination .pagination_number:hover {
	background-color:#aaaaaa; 
	color:black;
}
#media_embed .pagination .pagination_previous,
#media_embed .pagination .pagination_next {
	border:1px solid #999999; 
	color:#666666;
}
#media_embed .pagination .pagination_previous:hover,
#media_embed .pagination .pagination_next:hover {
	background-color:#aaaaaa; 
	color:black;
}
#media_embed .pagination .pagination_currentpage {
	background-color:#666666; 
	border:1px solid #666666; 
	color:white;
}