<?php
/**
 * Navigation
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 20px 0 10px;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	text-align: center;
	margin-left: -1px;
}
.elgg-pagination li:first-child a,
.elgg-pagination li:first-child span {
	border-radius: 3px 0 0 3px;
}
.elgg-pagination li:last-child a,
.elgg-pagination li:last-child span {
	border-radius: 0 3px 3px 0;
}
.elgg-pagination li:first-child a:before,
.elgg-pagination li:first-child span:before {
	content: "\ab";
	margin-right: 6px;
}
.elgg-pagination li:last-child a:after,
.elgg-pagination li:last-child span:after {
	content: "\bb";
	margin-left: 6px;
}
.elgg-pagination a,
.elgg-pagination span {
	display: inline-block;
	padding: 6px 15px;
	color: #444;
	border: 1px solid #DCDCDC;
}
.elgg-pagination a:hover {
	color: #999;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled span {
	color: #CCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #999;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	margin-bottom: 5px;
	border-bottom: 1px solid #DCDCDC;
	display: table;
	width: 100%;
}
.elgg-tabs li {
	float: left;
	border: 1px solid #DCDCDC;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 5px;
	border-radius: 3px 3px 0 0;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 4px 15px 6px;
	text-align: center;
	height: auto;
	color: #666;
}
.elgg-tabs a:hover {
	background: #DEDEDE;
	color: #444;
}
.elgg-tabs .elgg-state-selected {
	border-color: #DCDCDC;
	background: #FFF;
}
.elgg-tabs .elgg-state-selected a {
	position: relative;
	top: 1px;
	background: #FFF;
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 100%;
	font-weight: normal;
	line-height: 1.4em;
	padding: 0 10px 1px 0;
	color: #BABABA;
}
.elgg-breadcrumbs > li {
	display: inline-block;
}
.elgg-breadcrumbs > li:after {
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #0054a7;
	text-decoration: underline;
}
.elgg-main .elgg-breadcrumbs {
	position: relative;
	top: -1px;
	left: 0;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	float: left;
}

.elgg-menu-topbar > li {
	float: left;
	height: 33px;
}

.elgg-menu-topbar > li > a {
	padding-top: 5px;
	color: #EEE;
	margin: 0 15px;
}

.elgg-menu-topbar > li > a:hover {
	color: #60B8F7;
	text-decoration: none;
}

.elgg-menu-topbar-alt {
	float: right;
}

.elgg-menu-topbar .elgg-icon {
	vertical-align: middle;
	margin-top: -1px;
}

.elgg-menu-topbar > li > a.elgg-topbar-logo {
	margin-top: 0;
	padding-left: 5px;
	width: 38px;
	height: 20px;
}

.elgg-menu-topbar > li > a.elgg-topbar-avatar {
	width: 18px;
	height: 18px;
	padding-top: 7px;
}

/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	float: left;
	left: 0;
	top: 0;
	position: relative;
	z-index: 1;
}
.elgg-menu-site > li {
	float: left;
}
.elgg-menu-site > li > a {
	color: #FFF;
	padding: 14px 18px;
}
.elgg-menu-site > li > a:hover {
	text-decoration: none;
}
.elgg-menu-site > .elgg-state-selected > a,
.elgg-menu-site > li:hover > a {
	background-color: #60B8F7;
	color: #FFF;
}
.elgg-menu-site > li > ul {
	position: absolute;
	display: none;
	background-color: #FFF;
	border: 1px solid #DEDEDE;
	text-align: left;
	top: 47px;
	margin-left: 0;
	width: 180px;

	border-radius: 0 0 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}
.elgg-menu-site > li:hover > ul {
	display: block;
}
.elgg-menu-site-more li {
	width: 180px;
}
.elgg-menu-site-more > li > a {
	padding: 10px 20px;
	background-color: #FFF;
	color: #444;
}
.elgg-menu-site-more > li:last-child > a,
.elgg-menu-site-more > li:last-child > a:hover {
	border-radius: 3px;
}
.elgg-menu-site-more > li.elgg-state-selected > a,
.elgg-menu-site-more > li > a:hover {
	background-color: #F0F0F0;
	color: #444;
}
.elgg-more {
	width: 182px;
}
.elgg-more > a:after {
	content: "\bb";
	margin-left: 6px;
}
/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	float: right;
	margin-top: -1px;
}
.elgg-menu-title > li {
	display: inline-block;
	margin-left: 4px;
}

/* ***************************************
	FILTER MENU
*************************************** */
.elgg-menu-filter {
	margin-bottom: 5px;
	border-bottom: 1px solid #DCDCDC;
	display: table;
	width: 100%;
}
.elgg-menu-filter > li {
	float: left;
	border: 1px solid #DCDCDC;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 5px;
	border-radius: 3px 3px 0 0;
}
.elgg-menu-filter > li.elgg-state-selected a:hover {
	background: #FFFFFF;
}
.elgg-menu-filter > li > a {
	text-decoration: none;
	display: block;
	padding: 4px 15px 6px;
	text-align: center;
	height: auto;
	color: #666;
}
.elgg-menu-filter > li > a:hover {
	background: #DEDEDE;
	color: #444;
}
.elgg-menu-filter > .elgg-state-selected {
	border-color: #DCDCDC;
	background: #FFF;
}
.elgg-menu-filter > .elgg-state-selected > a {
	position: relative;
	top: 1px;
	background: #FFF;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page {
	margin-bottom: 15px;
}
.elgg-menu-page a {
	color: #444;
	display: block;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 0;
}
.elgg-menu-page a:hover {
	color: #999;
}
.elgg-menu-page li.elgg-state-selected > a {
	color: #999;
	text-decoration: underline;
}
.elgg-menu-page .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-page .elgg-state-selected > .elgg-child-menu {
	display: block;
}
.elgg-menu-page .elgg-menu-closed:before, .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\25B8";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\25BE";
}

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;
	overflow: hidden;
	min-width: 180px;
	max-width: 250px;
	border: 1px solid #DEDEDE;
	background-color: #FFF;

	border-radius: 0 3px 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #ddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover > li a {
	padding: 6px 18px;
}
.elgg-menu-hover a:hover {
	background-color: #F0F0F0;
	text-decoration: none;
}
.elgg-menu-hover-admin a {
	color: #FF0000;
}
.elgg-menu-hover-admin a:hover {
	color: #FFF;
	background-color: #FF0000;
}

/* ***************************************
	SITE FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-footer > li:after {
	content: "\007C";
	padding: 0 6px;
}

.elgg-menu-footer-default {
	float: right;
}

.elgg-menu-footer-alt {
	float: left;
}

.elgg-menu-footer-meta {
	float: left;
}

/* ***************************************
	GENERAL MENU
*************************************** */
.elgg-menu-general > li,
.elgg-menu-general > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-general > li:after {
	content: "\007C";
	padding: 0 6px;
}

/* ***************************************
	ENTITY AND ANNOTATION
*************************************** */
<?php // height depends on line height/font size ?>
.elgg-menu-entity, .elgg-menu-annotation {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #AAA;
	line-height: 16px;
	height: auto;
}
.elgg-menu-entity > li, .elgg-menu-annotation > li {
	margin-left: 15px;
}
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	color: #AAA;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	display: block;
}
.elgg-menu-entity > li > span, .elgg-menu-annotation > li > span {
	vertical-align: baseline;
}

/* ***************************************
	OWNER BLOCK
*************************************** */
.elgg-menu-owner-block li a {
	display: block;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 0;
	color: #444;
}
.elgg-menu-owner-block li a:hover {
	color: #999;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	color: #999;
	text-decoration: underline;
}

/* ***************************************
	LONGTEXT
*************************************** */
.elgg-menu-longtext {
	float: right;
}

/* ***************************************
	RIVER
*************************************** */
.elgg-menu-river {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #AAA;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-river > li {
	display: inline-block;
	margin-left: 5px;
}
.elgg-menu-river > li > a {
	color: #AAA;
	height: 16px;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-river > li > a {
	display: block;
}
.elgg-menu-river > li > span {
	vertical-align: baseline;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras {
	margin-bottom: 15px;
}
.elgg-menu-extras li {
	padding-right: 5px;
}

/* ***************************************
	WIDGET MENU
*************************************** */
.elgg-menu-widget > li {
	position: absolute;
	top: 8px;
	display: inline-block;
	width: 18px;
	height: 18px;
}
.elgg-menu-widget > .elgg-menu-item-collapse {
	left: 10px;
}
.elgg-menu-widget > .elgg-menu-item-delete {
	right: 10px;
}
.elgg-menu-widget > .elgg-menu-item-settings {
	right: 32px;
}
