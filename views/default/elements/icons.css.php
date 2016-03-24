<?php
/**
 * Elgg icons
 *
 * @package Elgg.Core
 * @subpackage UI
 */

?>
/* <style> /**/

/* ***************************************
	ICONS
*************************************** */
.elgg-icon {
	color: #CCC;
	font-size: 18px;
	line-height: 1em;
	margin: 0 2px;
}

:focus > .elgg-icon,
.elgg-icon:hover,
.elgg-icon-hover {
	color: #4690D6;
}

h1 > .elgg-icon,
h2 > .elgg-icon,
h3 > .elgg-icon,
h4 > .elgg-icon,
h5 > .elgg-icon,
h6 > .elgg-icon {
	font-size: 1em;
}

.elgg-avatar > .elgg-icon-hover-menu {
	display: none;
	position: absolute;
	right: 0;
	bottom: 0;
	margin: 0;
	cursor: pointer;
}
.elgg-avatar > .elgg-icon-hover-menu:before {
	position: absolute;
	right: 2px;
	bottom: 0px;
}

.elgg-ajax-loader {
	background: white url(<?= elgg_get_simplecache_url("ajax_loader_bw.gif"); ?>) no-repeat center center;
	min-height: 31px;
	min-width: 31px;
}

/* ***************************************
	AVATAR ICONS
*************************************** */
.elgg-avatar {
	position: relative;
	display: inline-block;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;

	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	border-radius: 3px;

	background-clip:  border;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;

	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	border-radius: 5px;

	background-clip:  border;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large {
	width: 100%;
}
.elgg-avatar-large > a > img {
	width: 100%;
	height: auto;
}
.elgg-state-banned {
	opacity: 0.5;
}