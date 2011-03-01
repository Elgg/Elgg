<?php
/**
 * Navigation
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGINATION
*************************************** */
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
.elgg-pagination .elgg-state-disabled span {
	color: #CCCCCC;
	border-color: #CCCCCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #555555;
	border-color: #555555;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	margin-bottom: 5px;
	border-bottom: 2px solid #cccccc;
	display: table;
	width: 100%;
}
.elgg-tabs li {
	float: left;
	border: 2px solid #cccccc;
	border-bottom: 0;
	background: #eeeeee;
	margin: 0 0 0 10px;
	-moz-border-radius: 5px 5px 0 0;
	-webkit-border-radius: 5px 5px 0 0;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0 10px;
	text-align: center;
	height: 21px;
	color: #999999;
}
.elgg-tabs a:hover {
	background: #dedede;
	color:#4690D6;
}
.elgg-tabs .elgg-state-selected {
	border-color: #cccccc;
	background: white;
}
.elgg-tabs .elgg-state-selected a {
	position: relative;
	top: 2px;
	background: white;
}

/* ***************************************
 * MENUS
 *
 * .elgg-menu does two things:
 *   1) Vertically centers inline images and icons
 *   2) Abstracts commonly-duplicated code used when adding "separators" to horizontal menus
 *
 * To make a menu horizontal:
 * .elgg-menu-$menu > li {display:inline-block}
 *
 * To add separators to a horizontal menu:
 * .elgg-menu-$menu > li > a {display:inline-block}
 * .elgg-menu-$menu > li:after {content: '|'}
 *************************************** */
.elgg-menu > li {vertical-align: middle;position:relative}
.elgg-menu > li:after {display: inline-block}
.elgg-menu > li:last-child:after {display:none}
.elgg-menu > li > a {display:block}
.elgg-menu img, .elgg-menu .elgg-icon {vertical-align: middle; margin-top: -2px}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs > li {
	display: inline-block;
}
.elgg-breadcrumbs > li:after{
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #0054a7;
	text-decoration: underline;
}

.elgg-main .elgg-breadcrumbs {
	position: relative;
	top: -6px;
	left: 0;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar {
	float: left;
}

.elgg-menu-topbar > li {
	float:left;
}

.elgg-menu-topbar > li > a {
	padding: 2px 15px;
	color: #eeeeee;
	margin-top: 2px;
	line-height: 1.1em;
}

.elgg-menu-topbar > li > a:hover {
	color: #71cbff;
	text-decoration: none;
}

.elgg-menu-topbar.elgg-section-alt {
	float:right;
}

/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site {
	position: absolute;
	height: 23px;
	bottom: 0;
	left: 0;
	width: auto;
	z-index: 7000;
}
.elgg-menu-site > li {
	display: inline-block;
	margin-right: 1px;
}
.elgg-menu-site li {
	height: 23px;
}
.elgg-menu-site a {
	color: white;
	font-weight: bold;
	padding: 3px 13px 0px 13px;
	height: 20px;
}
.elgg-menu-site a:hover {
	text-decoration: none;
}
.elgg-menu-site li.elgg-state-selected a,
.elgg-menu-site li a:hover,
.elgg-menu-site .elgg-more:hover a {
	background: white;
	color: #555555;
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-border-radius: 4px 4px 0 0;
	-webkit-border-radius: 4px 4px 0 0;
}

.elgg-more > a:before {
	content: "\25BC";
	font-size:smaller;
	margin-right: 4px;
}

.elgg-more > ul {
	display:none;
	position:relative;
	left: -1px;
	top: -1px;
	width: 100%;
}

.elgg-more:hover > ul {
	display:block;
}

.elgg-menu-site .elgg-more ul {
	z-index: 7000;
	min-width: 150px;
	border: 1px solid #999999;
	border-top: 0;
	-moz-border-radius: 0 0 4px 4px;
	-webkit-border-radius: 0 0 4px 4px;
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}
.elgg-menu-site .elgg-more ul li {
	display:block;
}
.elgg-menu-site .elgg-more:hover ul li a {
	background: white;
	color: #555555;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.elgg-menu-site .elgg-more ul li a:hover {
	background: #4690D6;
	color: white;
}
.elgg-menu-site .elgg-more ul li:last-child a,
.elgg-menu-site .elgg-more ul li:last-child a:hover {
	-moz-border-radius: 0 0 4px 4px;
	-webkit-border-radius: 0 0 4px 4px;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page a {
	display: block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color: white;
	margin: 0 0 3px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-page a:hover {
	background-color: #0054A7;
	color: white;
	text-decoration: none;
}
.elgg-menu-page li.elgg-state-selected > a {
	background-color: #4690D6;
	color: white;
}
.elgg-menu-page .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-page .elgg-menu-closed:before, .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\002B";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\002D";
}

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;

	width: 165px;
	border: solid 1px;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFFFFF;
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #dddddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover a {
	padding: 2px 8px;
	font-size: 92%;
}
.elgg-menu-hover a:hover {
	background: #cccccc;
	text-decoration: none;
}
.elgg-hover-admin a {
	color: red;
}
.elgg-hover-admin a:hover {
	color: white;
	background-color: red;
}

/* ***************************************
	FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	display: inline-block;
	color:#999;
}

.elgg-menu-footer > li:after {
	content: "\007C";
	padding: 0 4px;
}

.elgg-menu-footer.elgg-section-alt {
	float: right;
}

/* ***************************************
	ENTITY METADATA
*************************************** */
.elgg-menu-metadata {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
}
.elgg-menu-metadata > li {
	display: inline-block;
	margin-left: 15px;
}
.elgg-menu-metadata > li > a {
	color: #aaa;
}

/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	float:right;
}

.elgg-menu-title > li {
	display:inline-block
}