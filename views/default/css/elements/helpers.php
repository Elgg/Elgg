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

.clearfloat { 
	clear: both;
}

.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.hidden {
	display: none;
}

.centered {
	margin: 0 auto;
}

.center {
	text-align: center;
}

.right {
	float: right;
}

.left {
	float: left;
}

.link {
	cursor: pointer;
}

<?php @todo // do we need something like large and small? ?>
.large {
	font-size: 120%;
}

.small {
	font-size: 80%;
}

.elgg-discover .elgg-discoverable {
	display: none;
}

.elgg-discover:hover .elgg-discoverable {
	display: block;
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
.pan{padding:$none}
.pas{padding:$small}
.pam{padding:$medium}
.pal{padding:$large}
.ptn{padding-top:$none}
.pts{padding-top:$small}
.ptm{padding-top:$medium}
.ptl{padding-top:$large}
.prn{padding-right:$none}
.prs{padding-right:$small}
.prm{padding-right:$medium}
.prl{padding-right:$large}
.pbn{padding-bottom:$none}
.pbs{padding-bottom:$small}
.pbm{padding-bottom:$medium}
.pbl{padding-bottom:$large}
.pln{padding-left:$none}
.pls{padding-left:$small}
.plm{padding-left:$medium}
.pll{padding-left:$large}
.phn{padding-left:$none;padding-right:$none}
.phs{padding-left:$small;padding-right:$small}
.phm{padding-left:$medium;padding-right:$medium}
.phl{padding-left:$large;padding-right:$large}
.pvn{padding-top:$none;padding-bottom:$none}
.pvs{padding-top:$small;padding-bottom:$small}
.pvm{padding-top:$medium;padding-bottom:$medium}
.pvl{padding-top:$large;padding-bottom:$large}
.man{margin:$none}
.mas{margin:$small}
.mam{margin:$medium}
.mal{margin:$large}
.mtn{margin-top:$none}
.mts{margin-top:$small}
.mtm{margin-top:$medium}
.mtl{margin-top:$large}
.mrn{margin-right:$none}
.mrs{margin-right:$small}
.mrm{margin-right:$medium}
.mrl{margin-right:$large}
.mbn{margin-bottom:$none}
.mbs{margin-bottom:$small}
.mbm{margin-bottom:$medium}
.mbl{margin-bottom:$large}
.mln{margin-left:$none}
.mls{margin-left:$small}
.mlm{margin-left:$medium}
.mll{margin-left:$large}
.mhn{margin-left:$none;margin-right:$none}
.mhs{margin-left:$small;margin-right:$small}
.mhm{margin-left:$medium;margin-right:$medium}
.mhl{margin-left:$large;margin-right:$large}
.mvn{margin-top:$none;margin-bottom:$none}
.mvs{margin-top:$small;margin-bottom:$small}
.mvm{margin-top:$medium;margin-bottom:$medium}
.mvl{margin-top:$large;margin-bottom:$large}
CSS;
?>