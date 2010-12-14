<?php
/**
 * Reusable layout objects and elements
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

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

.elgg_inner {
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
