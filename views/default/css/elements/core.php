<?php
/**
 * Core CSS
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* ***************************************
	Core
*************************************** */
a:hover,
a.selected {
	text-decoration: underline;
}
p {
	margin-bottom:15px;
}
p:last-child {
	margin-bottom:0;
}
small {
	font-size: 90%;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color:#0054A7;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }
dt {
	font-weight: bold;
}
dd {
	margin: 0 0 1em 1em;
}
pre, code {
	background:#EBF5FF;
	color:#000000;
	overflow:auto;

	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
}
code {
	padding:2px 3px;
}
pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}
blockquote {
	padding:3px 15px;
	margin:0px 0 15px 0;
	background:#EBF5FF;
	border:none;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}


/* ***************************************
	GENERIC SELECTORS
*************************************** */
h2 {
/*	border-bottom:1px solid #CCCCCC; */
	padding-bottom:5px;
}

.clearfloat { clear:both; }

/* Clearfix! */
.clearfix:after,
.listing:after,
.listing .info:after {
	content:".";
	display:block;
	height:0;
	clear:both;
	visibility:hidden;
}

.listing .icon { float: left; margin-right: 10px; }
.listing .icon img { width: auto }
.listing .info { display: table-cell; }

.link {
	cursor:pointer;
}
.small {
	font-size: 90%;
}
.divider {
	border-top:1px solid #cccccc;
}
.hidden {
	display:none;
}
.radius8 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
.margin-none {
	margin:0;
}
.margin-top {
	margin-top:10px;
}
.rss-link {
	margin-top:-10px;
	margin-bottom:10px;
}
.rss-link a {
	display:block;
	width:14px;
	height:14px;
	float:right;
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: -250px top;
	text-indent: -1000em;
}
.elgg-tags {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: left -196px;
	padding:1px 0 0 14px;
	font-size: 85%;
}
.elgg-tagcloud {
	text-align:justify;
}


.right {
	float: right;
}

.left {
	float: left;
}

.elgg_hrt {
	border-top: 1px solid #CCCCCC;
}

.elgg_hrb {
	border-bottom: 1px solid #CCCCCC;
}
