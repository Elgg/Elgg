/* <style> /**/
	
/**
 * Navigation
 */

/* ***************************************
	ANCHORS & BADGES
*************************************** */

.elgg-anchor * {
	display: inline;
}

.elgg-anchor .elgg-icon {
	color: inherit;
	font-size: inherit;
}

.elgg-anchor img {
	vertical-align: middle;
}

.elgg-anchor * + .elgg-anchor-label {
	margin-left: 5px;
}

.elgg-badge {
	display: inline-block;
	margin-left: 5px;
	color: #fff;
	background-color: #777;
	padding: 3px 7px;
	line-height: 1;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	border-radius: 10px;
	font-size: 90%;
}

.elgg-menu-topbar .elgg-badge {
	background-color: #f00;
	font-weight: 700;
	box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
/*	font-size: 10px;*/
	position: absolute;
	top: 0;
	right: 0;
}

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
.elgg-pagination li:first-child > a,
.elgg-pagination li:first-child > span {
	border-radius: 3px 0 0 3px;
}
.elgg-pagination li:last-child > a,
.elgg-pagination li:last-child > span {
	border-radius: 0 3px 3px 0;
}
.elgg-pagination li:first-child > a:before,
.elgg-pagination li:first-child > span:before {
	content: "\ab";
	margin-right: 6px;
}
.elgg-pagination li:last-child > a:after,
.elgg-pagination li:last-child > span:after {
	content: "\bb";
	margin-left: 6px;
}
.elgg-pagination li > a,
.elgg-pagination li > span {
	display: inline-block;
	padding: 6px 15px;
	color: #444;
	border: 1px solid #DCDCDC;
}
.elgg-pagination li > a:hover {
	color: #999;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled > span {
	color: #CCC;
}
.elgg-pagination .elgg-state-selected > span {
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
	MENUS
*************************************** */
.elgg-menu-item-has-dropdown .elgg-child-menu {
	display: none;
}

.elgg-menu-item-has-toggle > .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-item-has-toggle.elgg-state-selected > .elgg-child-menu {
	display: block;
}

.elgg-menu > li > a .elgg-icon.elgg-state-opened,
.elgg-menu > li > a .elgg-icon.elgg-state-closed {
    font-size: 9px;
    color: inherit;
    line-height: inherit;
    margin: 0 5px;
}

.elgg-menu > li > .elgg-menu-opened .elgg-icon.elgg-state-opened {
	display: inline-block;
}
.elgg-menu > li > .elgg-menu-opened .elgg-icon.elgg-state-closed {
	display: none;
}
.elgg-menu > li > .elgg-menu-closed .elgg-icon.elgg-state-closed {
	display: inline-block;
}
.elgg-menu > li > .elgg-menu-closed .elgg-icon.elgg-state-opened {
	display: none;
}

.elgg-menu .elgg-anchor-icon {
	color: inherit;
	font-size: inherit;
}

.elgg-menu .elgg-anchor-icon + .elgg-anchor-label {
	margin-left: 5px;
}

.elgg-menu a:hover .elgg-icon {
	color: inherit;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	float: left;
}

.elgg-menu-topbar > li {
	display: inline-block;
}

.elgg-menu-topbar > li > a {
	color: #EEE;
	margin: 0 13px;
	line-height: 30px; /* topbar height minus border width */
	vertical-align: middle;
}

.elgg-menu-topbar > li > a:hover {
	color: #60B8F7;
	text-decoration: none;
}

.elgg-menu-topbar-alt {
	float: right;
}

.elgg-topbar-child-menu > li > a {
	padding: 10px 20px 10px 15px;
}

.elgg-menu-topbar-default .elgg-icon {
    font-size: 18px;
    vertical-align: middle;
}
.elgg-menu-topbar-default .elgg-anchor-label {
	display: none;
}
.elgg-button-nav {
	display: none;
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	color: #FFF;
	float: left;
	padding: 10px 18px;
}
.elgg-button-nav:hover {
	color: #FFF;
	text-decoration: none;
	background-color: #60B8F7;
}
.elgg-button-nav .elgg-icon-bars {
	font-size: 18px;
	color: #fff;
	vertical-align: middle;
}
@media (max-width: 1030px) {
	.elgg-menu-topbar-default > li:first-child a {
		margin-left: 0;
	}
	.elgg-menu-topbar-alt > li:last-child a {
		margin-right: 0;
	}
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
	z-index: 50;
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
	padding: 2.5px;
}
.elgg-menu-title > li {
	display: inline-block;
	padding: 2.5px;
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
	margin-bottom: 20px;
}
.elgg-menu-page-container > ul + ul {
	margin-top: 15px;
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
.elgg-menu-page .elgg-menu-closed:before,
.elgg-menu-page .elgg-menu-opened:before {
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
	border: 1px solid #DEDEDE;
	background-color: #FFF;
	margin-right: 10px;

	border-radius: 0 3px 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}

.elgg-menu-hover-card-container {
	display: flex;
	flex-wrap: wrap;
	max-width: 500px;
}

.elgg-menu-hover-card {
	padding: 8px 16px;
	min-width: 300px;
	flex: 2;
}

.elgg-menu-hover .elgg-menu > li a {
	padding: 8px 16px;
	color: #666;
}
.elgg-menu-hover .elgg-anchor-icon + .elgg-anchor-label {
	margin-left: 12px;
}
.elgg-menu-hover .elgg-menu a:hover {
	background-color: #F0F0F0;
	text-decoration: none;
}
.elgg-menu-hover-actions,
.elgg-menu-hover-default {
	border-left: 1px solid #efefef;
	flex: 1;
	white-space: nowrap;
}

.elgg-menu-hover-admin {
	border-top: 1px solid #efefef;
}

.elgg-menu-hover .elgg-menu-hover-admin a:hover {
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
.elgg-menu-entity-container,
.elgg-menu-annotation-container,
.elgg-menu-river-container {
	float: right;
	margin-left: 15px;
}
.elgg-menu-entity,
.elgg-menu-annotation,
.elgg-menu-river {
	font-size: 90%;
	color: #aaaaaa;
	line-height: normal;
	height: auto;
	vertical-align: middle;
}
.elgg-menu-entity > li:not(:first-child),
.elgg-menu-annotation > li:not(:first-child),
.elgg-menu-river > li:not(:first-child) {
	margin-left: 15px;
}
.elgg-menu-entity > li > a,
.elgg-menu-annotation > li > a,
.elgg-menu-river > li > a {
	color: inherit;
}
.elgg-menu-entity > li > a:hover,
.elgg-menu-annotation > li > a:hover,
.elgg-menu-river > li > a:hover {
	color: #5097cf;
	text-decoration: none;
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
.elgg-field-input > .elgg-menu-longtext {
	margin-top: -20px;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras {
	margin-bottom: 15px;
	color: #aaaaaa;
	font-size: 18px;
}
.elgg-menu-extras > li {
	padding-right: 15px;
}
.elgg-menu-extras > li > a {
	color: inherit;
}
.elgg-menu-extras > li > a:hover {
	color: #5097cf;
	text-decoration: none;
}
.elgg-menu-extras .elgg-menu-item-rss .elgg-anchor-label {
	display: none;
}

/* ***************************************
	WIDGET MENU
*************************************** */
.elgg-menu-widget-container {
	float: right;
	margin-right: 15px;
}
.elgg-menu-widget > li {
	display: inline-block;
	margin-left: 10px;
}

@media (max-width: 820px) {
	.elgg-menu-footer {
		float: none;
		text-align: center;
	}
	.elgg-menu-page,
	.elgg-sidebar .elgg-menu-owner-block {
		border-bottom: 1px solid #DCDCDC;
	}
	.elgg-menu-page a,
	.elgg-sidebar .elgg-menu-owner-block li a {
		border-color: #DCDCDC;
		border-style: solid;
		border-width: 1px 1px 0 1px;
		margin: 0;
		padding: 10px;
		background-color: #FFFFFF;
	}
	.elgg-menu-page a:hover,
	.elgg-sidebar .elgg-menu-owner-block li a:hover,
	.elgg-menu-page li.elgg-state-selected > a,
	.elgg-sidebar .elgg-menu-owner-block li.elgg-state-selected > a {
		color: #444;
		background-color: #F0F0F0;
		text-decoration: none;
	}
}

@media (min-width: 767px) {
	.elgg-nav-collapse {
		display: block !important;
	}
}

@media (max-width: 766px) {
	.elgg-button-nav {
		cursor: pointer;
		display: block;
	}
	.elgg-nav-collapse {
		clear: both;
		display: none;
		width: 100%;
	}
	#login-dropdown a {
		padding: 10px 18px;
	}
	.elgg-menu-site {
		float: none;
	}
	.elgg-menu-site > li > ul {
		position: static;
		display: block;
		left: 0;
		margin-left: 0;
		border: none;
		box-shadow: none;
		background: none;
	}
	.elgg-more,
	.elgg-menu-site-more li,
	.elgg-menu-site > li > ul {
		width: auto;
	}
	.elgg-menu-site ul li {
		float: none;
		margin: 0;
	}
	.elgg-more > a {
		border-bottom: 1px solid #294E6B;
	}
	.elgg-menu-site > li {
		border-top: 1px solid #294E6B;
		clear: both;
		float: none;
		margin: 0;
	}
	.elgg-menu-site > li:first-child {
		border-top: none;
	}
	.elgg-menu-site > li > a {
		padding: 10px 18px;
	}
	.elgg-menu-site-more > li > a {
		color: #FFF;
		background: none;
		padding: 10px 18px 10px 30px;
	}
	.elgg-menu-site-more > li:last-child > a,
	.elgg-menu-site-more > li:last-child > a:hover {
		border-radius: 0;
	}
	.elgg-menu-site-more > li.elgg-state-selected > a,
	.elgg-menu-site-more > li > a:hover {
		background-color: #60B8F7;
		color: #FFF;
	}
}

@media (max-width: 600px) {
	.elgg-menu-entity,
	.elgg-menu-annotation,
	.elgg-menu-river {
		margin-left: 0;
	}
	.elgg-menu-entity > li,
	.elgg-menu-annotation > li,
	.elgg-menu-river > li {
		margin-left: 0;
		margin-right: 15px;
	}
}
