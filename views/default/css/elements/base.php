<?php
/**
 *
 */


?>

<?php //@todo move to helpers.php ?>
.clearfloat { clear:both; }

/* Clearfix! */
.clearfix:after {
	content:".";
	display:block;
	height:0;
	clear:both;
	visibility:hidden;
}
.hidden {
	display:none;
}
.centered {
	margin:0 auto;
}
.center {
	text-align: center;
}

<?php //@todo move to core.php ?>
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