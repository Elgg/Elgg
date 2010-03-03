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
	background: white;
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
#facebox .footer a {
	-moz-outline: none;
	outline: none;
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


/* EMBED MEDIA TABS */
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
	font-size:1.35em;
	text-align: center;
	text-decoration: none;
	color:#b6b6b6;
	background: white;
	display: block;
	padding: 0 10px 0 10px;
	margin:0 10px 0 10px;
	height:25px;
	width:auto;
	border-top:2px solid #dedede;
	border-left:2px solid #dedede;
	border-right:2px solid #dedede;
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-topright: 8px;
	-webkit-border-top-left-radius: 8px;
	-webkit-border-top-right-radius: 8px;
}
/* IE6 fix */
* html #embed_media_tabs ul li a { display: inline; }

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
	/* top: 2px; - only needed if selected tab needs to sit over a border */
}

#mediaUpload,
#mediaEmbed {
	margin:0 5px 10px 5px;
	padding:10px;
	border:2px solid #dedede;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background: #dedede;
}
#mediaEmbed .search_listing {
	margin:0 0 5px 0;
	background: white;
}

h1.mediaModalTitle {
	/* color:#0054A7; */
	font-size:1.35em;
	line-height:1.2em;
	margin:0 0 0 8px;
	padding:5px;
}

#mediaEmbed .pagination,
#mediaUpload .pagination {
	float:right;
	padding:5px;
	background:white;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;	
}
#mediaUpload label {
	font-size:120%;
}
#mediaEmbed p.embedInstructions {
	margin:10px 0 5px 0;
}
a.embed_media {
	margin:0;
	float:right;
	display:block;
	text-align: right;
	font-size:1.0em;
	font-weight: normal;
}
label a.embed_media {
	font-size:0.8em;
}




/* ***************************************
	PAGINATION
*************************************** */
#mediaEmbed .pagination .pagination_number {
	border:1px solid #999999; 
	color:#666666;
}
#mediaEmbed .pagination .pagination_number:hover {
	background:#aaaaaa; 
	color:black;
}

#mediaEmbed .pagination .pagination_previous,
#mediaEmbed .pagination .pagination_next {
	border:1px solid #999999; 
	color:#666666;
}
#mediaEmbed .pagination .pagination_previous:hover,
#mediaEmbed .pagination .pagination_next:hover {
	background:#aaaaaa; 
	color:black;
}
#mediaEmbed .pagination .pagination_currentpage {
	background:#666666; 
	border:1px solid #666666; 
	color:white;
}










