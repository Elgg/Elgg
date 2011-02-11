<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Classic layout: 990 wide centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** CLASSIC LAYOUT ******/
.elgg-page-classic .elgg-page-header > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	height: 90px;
}
.elgg-page-classic .elgg-page-body > .elgg-inner {
	width: 990px;
	margin: 0 auto;
}
.elgg-page-classic .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #DEDEDE;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #333333 url(<?php echo elgg_get_site_url(); ?>_graphics/toptoolbar_background.gif) repeat-x top left;
	border-bottom: 1px solid #000000;
	min-width: 998px;
	position: relative;
	height: 24px;
	z-index: 9000;
}
.elgg-page-topbar > .elgg-inner {
	padding: 2px 10px 2px 8px;
}
<?php //@todo location-dependent styles ?>
.elgg-page-topbar a {
	color: #eeeeee;
	float: left;
	margin: 2px 30px 0 0;
	line-height: 1.1em;
}
.elgg-page-topbar a.elgg-alt {
	float: right;
	margin: 2px 0 0 30px;
}
.elgg-page-topbar a:hover {
	color: #71cbff;
	text-decoration: none;
}
<?php // elgg logo and user avatar need to be adjusted slightly ?>
.elgg-page-topbar img {
	margin-top: -1px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 24px;
	right: 20px;
	max-width: 500px;
	z-index: 1000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	position: relative;
	background-color: #4690D6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png);
	background-repeat: repeat-x;
	background-position: bottom left;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}
.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	text-shadow: 1px 2px 4px #333333;
	text-decoration: none;
}

/***** PAGE BODY ******/
.elgg-page-body > .elgg-inner {
	min-height: 360px;
}
#elgg-layout-one-column {
	padding: 10px 0;
}
#elgg-layout-sidebar {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif);
	background-repeat: repeat-y;
	background-position: right top;
}
#elgg-layout-two-sidebar {
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif);
	background-repeat: repeat-y;
	background-position: right top;
}
.elgg-aside {
	padding: 20px 10px;
	position: relative;
	min-height: 360px;
}
.elgg-sidebar {
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
}
.elgg-sidebar.elgg-alt {
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
.elgg-heading-main {
	float: left;
	max-width: 530px;
	margin-right: 10px;
}
.elgg-main > .elgg-head a {
	float: right;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
	z-index: 999;
}
<?php //@todo location-dependent styles ?>
.elgg-page-footer a {
	float: left;
}
.elgg-page-footer a.elgg-alt {
	float: right;
}
.elgg-page-footer .elgg-menu {
	float: left;
	width: 100%;
}
.elgg-page-footer, .elgg-page-footer a {
	color: #999999;
}
.elgg-page-footer a:hover {
	color: #666666;
}
