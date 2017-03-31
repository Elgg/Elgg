<?php
/**
 * Helpers CSS
 *
 * Contains generic elements that can be used throughout the site.
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

.clearfloat { 
	clear: both;
}

<?php /* Need .elgg-page to be able to override .elgg-menu-hz > li {display:inline-block} and such */ ?>
.hidden,
.elgg-page .hidden,
.elgg-menu > li.hidden {
	display: none;
}

.centered {
	margin: 0 auto;
}

.center,
.elgg-justify-center {
	text-align: center;
}

.elgg-justify-right {
	text-align: right;
}

.elgg-justify-left {
	text-align: left;
}

.float {
	float: left;
}

.float-alt {
	float: right;
}

.link {
	cursor: pointer;
}

.elgg-discover .elgg-discoverable {
	display: none;
}

.elgg-discover:hover .elgg-discoverable {
	display: block;
}

.elgg-transition:hover,
.elgg-transition:focus,
:focus > .elgg-transition {
	opacity: .7;
}

/* ***************************************
	BORDERS AND SEPARATORS
*************************************** */
.elgg-border-plain {
	border: 1px solid #eeeeee;
}
.elgg-border-transition {
	border: 1px solid #eeeeee;
}
.elgg-divide-top {
	border-top: 1px solid #CCCCCC;
}
.elgg-divide-bottom {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-divide-left {
	border-left: 1px solid #CCCCCC;
}
.elgg-divide-right {
	border-right: 1px solid #CCCCCC;
}
