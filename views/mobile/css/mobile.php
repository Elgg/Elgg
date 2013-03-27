<?php
/*
 *
 * Elgg Mobile CSS
 *
 */
?>

/***** MOBILE ******/
html {
    font-size: 100%;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
}
body {
	font-size: 90%;
}

/***** LAYOUT ******/
.elgg-page-default {
	width: auto;
	min-width: 0;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	width: auto;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: auto;
	padding: 5px 10px;
}
.elgg-menu-footer-alt,
.elgg-menu-footer-default {
    display: block;
    float: none;
    text-align: center;
}
/***** PAGE BODY LAYOUT ******/
.elgg-page-body {
	padding: 0;
}
.elgg-layout-one-sidebar,
.elgg-layout-two-sidebar {
	background: none;
	width: 100%;
    float: left;
}
/***** PAGE FOOTER ******/
.elgg-page-footer {
    padding-bottom: 20px;
}
/***** HEADER ******/
.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 1.4em;
    padding-left: 20px;
}
/***** SIDEBAR ******/
.elgg-sidebar {
	background: #F0F0F0;
	width: 100%;
	float: left;
	padding: 10px 20px;
	margin: 0 0 10px 0;
	
	-webkit-box-sizing: border-box;
	-moz-box-sizing: 	border-box;
	box-sizing: 		border-box;
}
/***** GROUPS ******/
.groups-profile-fields {
	float: left;
}
.groups-profile-fields .odd,
.groups-profile-fields .even {
	padding: 5px 10px;
}
#groups-tools > li {
	width: auto;
	float: none;
	margin-bottom: 10px;
}
#groups-tools > li:nth-child(odd) {
	margin-right: 0;
}
.groups-stats {
	padding: 5px 10px;
    margin-bottom: 5px;
}
/***** MISCELLANEOUS ******/
.elgg-search-header {
	position: relative;
	float: left;;
}


