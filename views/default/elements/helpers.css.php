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
	border: 1px solid $(border-color-soft);
}
.elgg-border-transition {
	border: 1px solid $(border-color-soft);
}
.elgg-divide-top {
	border-top: 1px solid $(border-color-soft);
}
.elgg-divide-bottom {
	border-bottom: 1px solid $(border-color-soft);
}
.elgg-divide-left {
	border-left: 1px solid $(border-color-soft);
}
.elgg-divide-right {
	border-right: 1px solid $(border-color-soft);
}

/* ***************************************
	Spacing (from OOCSS)
*************************************** */
<?php
/**
 * Spacing classes
 * Should be used to modify the default spacing between objects (not between nodes of the same object)
 * Please use judiciously. You want to be using defaults most of the time, these are exceptions!
 * <type><location><size>
 * <type>: m = margin, p = padding
 * <location>: a = all, t = top, r = right, b = bottom, l = left, h = horizontal, v = vertical
 * <size>: n = none, s = small, m = medium, l = large
 */

$none = '0';
$small = '5px';
$medium = '10px';
$large = '20px';

echo <<<CSS
/* Padding */
.pan{padding:$none}
.prn, .phn{padding-right:$none}
.pln, .phn{padding-left:$none}
.ptn, .pvn{padding-top:$none}
.pbn, .pvn{padding-bottom:$none}

.pas{padding:$small}
.prs, .phs{padding-right:$small}
.pls, .phs{padding-left:$small}
.pts, .pvs{padding-top:$small}
.pbs, .pvs{padding-bottom:$small}

.pam{padding:$medium}
.prm, .phm{padding-right:$medium}
.plm, .phm{padding-left:$medium}
.ptm, .pvm{padding-top:$medium}
.pbm, .pvm{padding-bottom:$medium}

.pal{padding:$large}
.prl, .phl{padding-right:$large}
.pll, .phl{padding-left:$large}
.ptl, .pvl{padding-top:$large}
.pbl, .pvl{padding-bottom:$large}

/* Margin */
.man{margin:$none}
.mrn, .mhn{margin-right:$none}
.mln, .mhn{margin-left:$none}
.mtn, .mvn{margin-top:$none}
.mbn, .mvn{margin-bottom:$none}

.mas{margin:$small}
.mrs, .mhs{margin-right:$small}
.mls, .mhs{margin-left:$small}
.mts, .mvs{margin-top:$small}
.mbs, .mvs{margin-bottom:$small}

.mam{margin:$medium}
.mrm, .mhm{margin-right:$medium}
.mlm, .mhm{margin-left:$medium}
.mtm, .mvm{margin-top:$medium}
.mbm, .mvm{margin-bottom:$medium}

.mal{margin:$large}
.mrl, .mhl{margin-right:$large}
.mll, .mhl{margin-left:$large}
.mtl, .mvl{margin-top:$large}
.mbl, .mvl{margin-bottom:$large}
CSS;
