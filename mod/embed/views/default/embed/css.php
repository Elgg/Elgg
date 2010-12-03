<?php
/**
 * Elgg embed CSS - standard across all themes
 * 
 * @package embed
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
	padding: 10px 15px 20px 15px;
	background-color: white;
	width: 730px;
	min-height:400px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
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
	margin:-4px 0 0 0;
	padding:0;
}
#facebox .footer img.close_image {
	background: url(<?php echo elgg_get_site_url(); ?>mod/embed/images/close_button.gif) no-repeat left top;
}
#facebox .footer img.close_image:hover {
	background: url(<?php echo elgg_get_site_url(); ?>mod/embed/images/close_button.gif) no-repeat left -31px;
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

#facebox .body .content h2 {
	color:#333333;
}


/* embeded content links */
.embeded_file.link {
	display: inline;
	margin-right: 5px;
	background-repeat:no-repeat;
	-webkit-background-size: 12px 16px;
	-moz-background-size: 12px 16px; 
	padding-left:14px;
}

/* entity listings */
.embed_data .entity-listing-icon img {
	cursor: pointer;
}
.embed_data .entity-listing  {
	border-bottom:none;
}
.embed_data:last-child {
	border-bottom:1px dotted #CCCCCC;
}
.embed_modal_videolist .entity-listing-icon img {
	width:75px;
	height:auto;
}
.embed_modal_document .embed_data .entity-listing-info {
	margin-top:0;
	margin-left:7px;
}
.embed_modal_tidypics .embed_data .entity-listing-info {
	margin-top:11px;
}
.embed_modal_file .embed_data .entity-listing-info {
	margin-top:0px;
	margin-left:9px;
}
.embed_data .entity-listing-info {
	width:auto;
	margin-top:15px;
	margin-left:15px;
	float:left;
}
.embed_data .entity-listing {
	cursor:pointer;
}

/* input field classes */
#facebox .content .input-text,
#facebox .content .input-tags {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
	background-color: white;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
#facebox .content .input-text:focus,
#facebox .content .input-tags:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333333;
}
#facebox .content .input-file {
	background-color: white;
}
#facebox .content p {
	color:#333333;
}
#facebox .content p.entity-title {
	color:#666666;
}
#facebox .content .entity-listing:hover {
	background-color: #eeeeee;
}
#facebox .content label {
	color:#333333;
}


/* ***************************************
	ELGG TABBED PAGE NAVIGATION
*************************************** */
#facebox .body .elgg-horizontal-tabbed-nav {
	margin-bottom:5px;
	padding: 0;
	border-bottom: 2px solid #cccccc;
	display:table;
	width:100%;
}
#facebox .body .elgg-horizontal-tabbed-nav ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
#facebox .body .elgg-horizontal-tabbed-nav li {
	float: left;
	border: 2px solid #cccccc;
	border-bottom-width: 0;
	background: #eeeeee;
	margin: 0 0 0 10px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-webkit-border-top-left-radius:5px;
	-webkit-border-top-right-radius:5px;
}
#facebox .body .elgg-horizontal-tabbed-nav a {
	text-decoration: none;
	display: block;
	padding:3px 10px 0 10px;
	text-align: center;
	height:21px;
	color:#999999;
}
#facebox .body .elgg-horizontal-tabbed-nav a:hover {
	background: #dedede;
	color:#666666;
}
#facebox .body .elgg-horizontal-tabbed-nav .selected {
	border-color: #cccccc;
	background: white;
}
#facebox .body .elgg-horizontal-tabbed-nav .selected a {
	position: relative;
	top: 2px;
	background: white;
}



/* Pagination (override core elgg css defaults) */
#facebox .body .pagination {
	float:right;	
}
#facebox .body .pagination .pagination-number {
	border:1px solid #999999; 
	color:#666666;
}
#facebox .body .pagination .pagination-number:hover {
	background-color:#aaaaaa; 
	color:black;
}
#facebox .body .pagination .pagination-previous,
#facebox .body .pagination .pagination-next {
	border:1px solid #999999; 
	color:#666666;
}
#facebox .body .pagination .pagination-previous:hover,
#facebox .body .pagination .pagination-next:hover {
	background-color:#aaaaaa; 
	color:black;
}
#facebox .body .pagination .pagination-currentpage {
	background-color:#666666; 
	border:1px solid #666666; 
	color:white;
}