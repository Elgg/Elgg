<?php
/**
 * Core CSS
 * 
 * This file holds all the complicated/hacky stuff that you really
 * shouldn't touch or override unless you're sure you know what you're doing.
 * 
 * Provides classes that implement cross-browser support for the following features:
 *   * clearfix
 *   * fluid-width content area that doesn't wrap around floats
 *   * menu's with separators
 *   * inline-block
 *   * horizontal menus
 *   * fluid gallery without using tables
 */
?>

/* Clearfix */
.clearfix:after,
.elgg-grid:after,
.elgg-layout:after,
.elgg-inner:after,
.elgg-page-header:after,
.elgg-page-footer:after,
.elgg-head:after,
.elgg-foot:after,
.elgg-col:after,
.elgg-image-block:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;	
}

/* Fluid width container that does not wrap floats */
.elgg-body,
.elgg-col-last {
	display: block;
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
	
	/* IE 6, 7 */
	zoom:1;
	*overflow:visible;
}

<?php //@todo isn't this only needed if we use display:table-cell? ?>
.elgg-body:after,
.elgg-col-last:after {
	display: block;
	visibility: hidden;
	height: 0 !important;
	line-height: 0;
	
	/* Stretch to fill up available space */
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}

/* ***************************************
 * MENUS
 *
 * To add separators to a menu:
 * .elgg-menu-$menu > li:after {content: '|'; background: ...;}
 *************************************** */
/* Enabled nesting of dropdown/flyout menus */
.elgg-menu > li { position: relative; }

/* Separators should only come between list items */
.elgg-menu > li:last-child:after { display: none } 

/* Maximize click target */
.elgg-menu > li > a { display: block }

/* Horizontal menus w/ separator support */
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a,
.elgg-menu-hz > li > span {
	vertical-align: middle;
}

/* Allow inline image blocks in horizontal menus */
.elgg-menu-hz .elgg-body:after { content: '.'; }

<?php //@todo This isn't going to work as-is.  Needs testing ?>
/* Inline block */
.elgg-gallery > li,
.elgg-button,
.elgg-icon,
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a,
.elgg-menu-hz > li > span {
	/* Google says do this, but why? */
	position: relative;
	
	/* FF2 */
	display: -moz-inline-box;

	display: inline-block;
	
	/* Inline-block: IE 6, 7 */
	zoom: 1;
	*display: inline;
}

/* Looks much better when middle-aligned with surrounding text */
.elgg-icon {vertical-align:middle}