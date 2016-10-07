<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
.elgg-page-default {
	min-width: 800px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #DEDEDE;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #333333 url(<?= elgg_get_simplecache_url("toptoolbar_background.gif"); ?>) repeat-x top left;
	border-bottom: 1px solid #000000;
	padding: 0 10px;
	position: relative;
	height: 24px;
	z-index: 9000;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 24px;
	right: 20px;
	max-width: 500px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	padding: 0 10px;
	position: relative;
	background: #4690D6 url(<?= elgg_get_simplecache_url("header_shadow.png"); ?>) repeat-x bottom left;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}

/***** PAGE BODY LAYOUT ******/
.elgg-page-body {
	padding: 0 10px;
}

.elgg-layout {
	min-height: 360px;
}
.elgg-layout-one-sidebar {
	background: transparent url(<?= elgg_get_simplecache_url("sidebar_background.gif"); ?>) repeat-y right top;
}
.elgg-layout-two-sidebar {
	background: transparent url(<?= elgg_get_simplecache_url("two_sidebar_background.gif"); ?>) repeat-y right top;
}
.elgg-layout-one-sidebar,
.elgg-layout-two-sidebar {
	display: -webkit-box;
	display: -webkit-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: start;
	-webkit-align-items: flex-start;
	-ms-flex-align: start;
	align-items: flex-start;
}
.elgg-layout-one-sidebar > .elgg-body,
.elgg-layout-two-sidebar > .elgg-body {
	-webkit-box-flex: 1;
	-webkit-flex: 1;
	-ms-flex: 1;
	flex: 1;
}
.elgg-layout-one-sidebar > .elgg-sidebar {
	float: none;
	-webkit-box-ordinal-group: 2;
	-webkit-order: 1;
	-ms-flex-order: 1;
	order: 1;
}
.elgg-layout-two-sidebar > .elgg-sidebar {
	float: none;
	-webkit-box-ordinal-group: 3;
	-webkit-order: 2;
	-ms-flex-order: 2;
	order: 2;
}
.elgg-layout-two-sidebar > .elgg-sidebar-alt {
	float: none;
	-webkit-box-ordinal-group: 1;
	-webkit-order: 0;
	-ms-flex-order: 0;
	order: 0;
}
.elgg-layout-widgets > .elgg-widgets {
	float: right;
}
.elgg-sidebar {
	position: relative;
	padding: 20px 10px;
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
}
.elgg-sidebar-alt {
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
}
.elgg-main > .elgg-head {
	padding-bottom: 3px;
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 10px;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	color: #999;
	padding: 0 10px;
	position: relative;
}

.elgg-page-footer a:hover {
	color: #666;
}
