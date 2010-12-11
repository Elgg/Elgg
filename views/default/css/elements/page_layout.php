<?php
/**
 * Page Layout
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* ***************************************
	Page Layout
*************************************** */

/* move elgg-body to one of the css components */
/**
 * elgg-body fills the space available to it.
 * It uses hidden text to expand itself. The combination of auto width, overflow
 * hidden, and the hidden text creates this effect.
 *
 * This allows us to float fixed width divs to either side of an .elgg-body div
 * without having to specify the body div's width.
 *
 * @todo check what happens with long <pre> tags or large images
 */
.elgg-body {
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
}
.elgg-body:after {
	display: block;
	visibility: hidden;
	height: 0 !important;
	line-height: 0;
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}



.elgg-sidebar .elgg-module {
	margin-top: 20px;
}

.elgg_inner {
}

.elgg-sidebar .elgg-module .elgg-head {
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 5px;
	padding-bottom: 5px;
}

.elgg-footer {
}

.elgg-media {
	padding: 3px 0;
}

.elgg-media .elgg-pict {
	float: left;
	margin-right: 5px;
}
.elgg-media .elgg-pict-alt {
	float: right;
	margin-left: 5px;
}



.elgg-list {
    border-top: 1px dotted #CCCCCC;
	margin: 5px 0;
	clear: both;
}

.elgg-list li {
	border-bottom: 1px dotted #CCCCCC;
}


.elgg-center {
	margin: 0 auto;
}

.elgg-width-classic {
	width: 990px;
}

/* ***************************************
	ELGG TOPBAR
*************************************** */
.elgg-page-topbar {
	background: #333333 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	border-bottom: 1px solid #000000;
	min-width: 998px;
	position: relative;
	height: 24px;
	z-index: 9000;
}
.elgg-page-header .elgg-inner {
	width: 990px;
	margin: 0 auto;
	height: 90px;
	position: relative;
}
.elgg-page-topbar .elgg-inner {
	padding: 2px 10px 2px 8px;
}
.elgg-page-topbar a {
	color: #eeeeee;
	float: left;
	margin: 2px 30px 0 0;
	line-height: 1.1em;
}
.elgg-page-topbar a.alt {
	float: right;
	margin: 2px 0 0 30px;
}
.elgg-page-topbar a:hover {
	color: #71cbff;
	text-decoration: none;
}
/* elgg logo and user avatar need to be adjusted slightly */
.elgg-page-topbar img {
	margin-top: -1px;
}

/* ***************************************
	HEADER
*************************************** */
.elgg-page-header {
	x-overflow: hidden;
	position: relative;
	background-color: #4690D6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png);
	background-repeat: repeat-x;
	background-position: bottom left;
}
.elgg-page-header h1 a {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	text-shadow: 1px 2px 4px #333333;
	text-decoration: none;
}
/* ***************************************
	BODY
*************************************** */

.elgg-layout-one_column {
	padding: 10px 0;
}
.elgg-layout-sidebar {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif);
	background-repeat:repeat-y;
	background-position: right top;
}
.elgg-layout-two-sidebar {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif);
	background-repeat:repeat-y;
	background-position: right top;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
}
.elgg-aside {
	padding: 20px 10px;
	position: relative;
	min-height: 360px;
}
.elgg-sidebar {
	float: right;
	width: 210px;
	margin-left: 10px;
}
.elgg-sidebar-alt {
	float: left;
	width: 160px;
	margin-right: 10px;
}

.elgg-main-header {
    border-bottom: 1px solid #CCCCCC;
    padding-bottom: 3px;
}

.elgg-main-heading {
	float: left;
	max-width: 530px;
	margin-right: 10px;
}

/* ***************************************
	FOOTER
*************************************** */
.elgg-page-footer {
	position: relative;
	z-index: 999;
}
.elgg-page-footer .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #DEDEDE;
}

.elgg-page-footer .elgg-inner,
.elgg-page-footer .elgg-inner a,
.elgg-page-footer .elgg-inner p {
	color:#999999;
}
.elgg-page-footer .elgg-inner a:hover {
	color:#666666;
}
.elgg-page-footer .elgg-inner p {
	margin:0;
}
