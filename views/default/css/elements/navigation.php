<?php
/**
 * Navigation
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* ***************************************
	Navigation
*************************************** */

/* Pagination */
.elgg-pagination {
	margin: 10px 0;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	margin: 0 6px 0 0;
	text-align: center;
}
.elgg-pagination a, .elgg-pagination span {
	padding: 2px 6px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	color: #4690d6;
	border: 1px solid #4690d6;
	font-size: 12px;
}
.elgg-pagination a:hover {
	background: #4690d6;
	color: white;
	text-decoration: none;
}
.elgg-pagination .inactive {
	color: #CCCCCC;
	border-color: #CCCCCC;
}
.elgg-pagination .active {
	color: #555555;
	border-color: #555555;
}
/* Tabs */
.elgg-horizontal-tabbed-nav {
	margin-bottom:5px;
	padding: 0;
	border-bottom: 2px solid #cccccc;
	display:table;
	width:100%;
}
.elgg-horizontal-tabbed-nav ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.elgg-horizontal-tabbed-nav li {
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
.elgg-horizontal-tabbed-nav a {
	text-decoration: none;
	display: block;
	padding:3px 10px 0 10px;
	text-align: center;
	height:21px;
	color:#999999;
}
.elgg-horizontal-tabbed-nav a:hover {
	background: #dedede;
	color:#4690D6;
}
.elgg-horizontal-tabbed-nav .selected {
	border-color: #cccccc;
	background: white;
}
.elgg-horizontal-tabbed-nav .selected a {
	position: relative;
	top: 2px;
	background: white;
}
/* Breadcrumbs */
.elgg-breadcrumbs {
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs li {
	display: inline;
}
.elgg-breadcrumbs li:after{
	content: "\003E";
	display: inline-block;
	padding: 0 4px 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs li:last-child:after {
	content: "";
}
.elgg-breadcrumbs a {
	color: #999999;
}
.elgg-breadcrumbs a:hover {
	color: #0054a7;
	text-decoration: underline;
}
.elgg-main .elgg-breadcrumbs {
	position: relative;
	top:-6px;
	left:0;
}
/* Menu */
.submenu {
	margin:0;
	padding:0;
	list-style: none;
}
.submenu ul {
	margin-bottom:0;
	padding-left:0;
	list-style: none;
}
.submenu li.selected a,
.submenu li.selected li.selected a,
.submenu li.selected li.selected li.selected a {
	background: #4690D6;
	color:white;
}
.submenu li a {
	display:block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
}
.submenu li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}
.submenu .child li a {
	margin-left:15px;
	background-color:white;
	color:#4690D6;
}
.submenu .child li a:hover {
	background:#0054A7;
	color:white;
	text-decoration:none;
}

.navigation,
.navigation ul {
	margin:0;
	padding:0;
	display:inline;
	float:left;
	list-style-type: none;
	z-index: 7000;
	position: relative;
}
.navigation li {
	list-style: none;
	font-weight: bold;
	position: relative;
	display:block;
	height:23px;
	float:left;
	margin:0;
	padding:0;
}
.navigation a {
	color:white;
	margin:0 1px 0 0px;
	text-decoration:none;
	font-weight: bold;
	font-size: 1em;
	padding:3px 13px 0px 13px;
	height:20px;
	cursor: pointer;
	display:block;
}
.navigation li a:hover {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
.navigation li.selected a {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	margin-top:1px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more {
	overflow:hidden;
}
li.navigation-more:hover {
	overflow:visible;
}
li.navigation-more:hover a {
	background:white;
	color:#555555;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more a.subnav span {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: -150px -56px;
	padding-left: 12px;
}
li.navigation-more:hover a.subnav span,
li.navigation-more a.subnav:hover span {
	background-position: -150px -76px;
}
li.navigation-more ul {
	z-index: 7000;
	min-width: 150px;
	margin-left:-1px;
	background-color:white;
	border-left:1px solid #999999;
	border-right:1px solid #999999;
	border-bottom:1px solid #999999;
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-left-radius:4px;
	-webkit-border-bottom-right-radius:4px;
	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}
li.navigation-more ul li {
	float:none;
}
.navigation li.navigation-more ul li a {
	background:white;
	color:#555555;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation-more ul li:last-child a,
.navigation li.navigation-more ul li:last-child a:hover {
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-left-radius:4px;
	-webkit-border-bottom-right-radius:4px;
}
.navigation li.navigation-more ul li a:hover {
	background:#4690D6;
	color:white;
	margin:0;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.navigation li.navigation-more ul li.selected a {
	background:#4690D6;
	color:white;
}
