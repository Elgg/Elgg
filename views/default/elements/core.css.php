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
/* <style> /**/

/* Clearfix */
.clearfix:after {
	visibility: hidden;
	display: block;
	font-size: 0;
	content: " ";
	clear: both;
	height: 0;
}

