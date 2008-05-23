<?php

	/**
	 * Elgg CSS
	 * The standard CSS file
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author (s) David Tosh / Pete Harris
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['wwwroot'] The site URL
	 */

?>

/*
    Default Elgg CSS
*/

/*
  Globals
*/

* {
	/* border: 0; */
	margin: 0;
	padding: 0;
}

body {
    background:#ffffff; /* url(<?php echo $vars['url']; ?>/_graphics/header.gif) repeat-x; */
	color:#697C83;
	font-family:'Trebuchet MS','Lucida Grande', Arial, sans-serif;
	text-align:left;
	margin:0 auto;
	padding:0;
	font-size: 80%;
	line-height:1.6em;
}

p {
	color:#555;
	margin:0 0 10px 0;
}

a {
	text-decoration:none;
	color:#336699;
}

a:hover {
	text-decoration:underline;
}

ol, ul {
	color:#697C83;
}

h1 {
	font-size:130%;
}

h2, h3, h4, h5 {
	font-size:100%;
}

input { vertical-align:middle; }

blockquote{
	background: #EEE url(<?php echo $vars['url']; ?>/_graphics/blockquote.png) no-repeat bottom left;
	padding: 10px;
	padding-bottom: 40px;
	margin: 1em;
}

/******* End of globals *************/

/*
	TABLES IN ELGG
*/

td {
	border:1px solid #eee;
	padding:0;
	margin:0;
}

td h4, h3, h5 {
	padding:0;
	margin:0;
}

/******** end tables **************/

/*
	CONTAINER: WRAPS THE HEADER AND MAIN CONTENT AREA BELOW THE USER TOOLBAR
*/

div#container {
	width:970px;
	margin:0 auto;
	padding:0;
}

/*
	HEADER: THE HEADER INCLUDES THE LOGO AND SEARCH BAR
*/

div#header {
	margin:0 0 10px 0;
	padding:5px;
	text-align:left;
	position:relative;
	background:#006699;
	width:100%;
	height:50px;
}

div#header h1 {
	font-size:20px;
	padding:0;
	margin:0;
}

div#header h2 {
	font-size:16px;
	padding:5px 0 0 0;
	margin:0;
	color:#ffffff;
}

div#header h1 a{
	color:#fff;
	/* font-family:Helvetica, "Myriad Web", Arial, sans-serif;*/
}

/**** End header ****/

/*
  Topmenu
*/

#topmenu {
    position:absolute;
    top:2px;
    right:10px;
    width: 500px;
    text-align: right;
}

#topmenu li {
    display:inline;
    list-style:none;
}

#topmenu li a {
    color:#fff;
    margin:0 10px 0 10px;
}

#topmenu .usericon {
	float:right;
}

/**** end top menu ****/

/*
    sidebar menu
*/

#sidebar_menu {
	width: 150px;
	margin:0;
	padding:0;
	float: left;
	background:#f5f5f5; /* url(<?php echo $vars['url']; ?>/_graphics/sidebar-top.gif) no-repeat top;*/
}

#sidebar_right {
	width:380px;
	min-height: 380px;
	float:right;
	margin: 0;
	padding: 0;
	border-top:1px solid white;
}

#mainContent {
    margin:0 0 0 160px;
    width:810px;
    min-height: 380px;
    padding:0;
    border-top:1px solid white;
}

#login-box {
    text-align:left;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
}

/*
	FOOTER
*/

div#footer {
	clear: both;
	position: relative;
	font-size:1em;
	height:26px;
	margin:20px 0 0 0;
	color:#174764;
	font-weight:bold;
	width:100%;
	padding:3px 0 0 0;
	background:url(<?php echo $vars['url']; ?>/_graphics/footer.gif) repeat-x;
}

div#footer img {
   text-align:right;
   padding:3px 0 0 0;
}

div#footer p {
   padding:0 20px 0 20px;
}

div#footer a {
	color:#fff;
	padding:0 5px 0 5px;
}

div#footer a:hover {
	text-decoration:underline;
}

div#footer a:link, div#footer a:visited {
	text-align:right;
}

/*
  System messages
*/

.messages {
    border:1px solid #D3322A;
    background:#F7DAD8;
    color:#000;
    padding:3px 50px;
    margin:20px 20px 10px 20px;
}

/*
  Forms
*/

.input-text,
.input-tags,
.input-url,
.input-textarea {
	/* width: 600px;*/
	width:95%;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	line-height: 1.4;
	font-family: inherit;
	padding:5px;
}

.input-textarea {
	height: 200px;
}

/* 
	elgg toolbar
*/

.elggtoolbar ul.drawers {
	width: 150px;
	margin: 0;
	padding: 0;
}

.elggtoolbar li.drawer ul li {
	line-height: 1.2em;
	margin: 0;
	padding: 3px 0 3px 0;
}

.elggtoolbar ul {
	list-style: none;
	margin: 0;
	padding: 3px 3px 3px 10px;
}

.elggtoolbar li a {
	text-decoration: none;
	color: #666;
	line-height:0.5em;
}
.elggtoolbar li a:hover {
	text-decoration: underline;
}

.elggtoolbar h2.drawer-handle {
	margin: 0;
	padding: 1px 1px 1px 10px;
	background-color: #e5e5e5;
	cursor: pointer;
	font-size: 100%;
}

.elggtoolbar h2.drawer-handle.open {
	color:#ffffff;
	background-color: #666666;
}

/* 
	rounded corner widget boxes
*/	
.ui-sortable { color:#222; margin:0; padding:0 10px 10px; }
.ui-sortable h2 {color:#555; font-size:11px; margin:0; line-height:2; padding:0; }

.ui-sortable-helper {
	/* width:150px !important; */
}

.placeholder {
	border:2px dashed #AAA;
	margin-left:-12px;
}


.sort {
	margin:10px 0;
	position:relative;
	/* margin:0px auto;*/
	z-index:1;
	margin-left:12px; /* default, width of left corner */
	margin-bottom:0.5em; /* spacing under box */
}

.sort h1 { 
	color:#5d9ed6;
	font-size:14px;
	margin:0 43px 0 0; /* margin so edit buttons are not obscured */
	cursor:move;
	height:1.8em;
	padding:8px 0 0 0;
	position:relative;
}

.sort .content,
.sort .t,
.sort .b,
.sort .b div {
	background:transparent url(<?php echo $vars['url']; ?>/_graphics/box.png) no-repeat top right;
	_background-image:url(<?php echo $vars['url']; ?>/_graphics/box.gif); /* for ie 6 as can't use AlphaImageLoader due to div width/height not being known */
    /* _filter:progid:DXImageTransform.Microsoft.AlphaImageLoader (src='images/box.png', sizingMethod='crop'); */
}

.sort .content {
	position:relative;
	zoom:1;
	_overflow-y:hidden;
	padding:0 12px 0 0;
}

.sort .t {
	/* top+left vertical slice */
	position:absolute;
	left:0px;
	top:0px;
	width:12px; /* top slice width */
	margin-left:-12px;
	height:100%;
	_height:1600px; /* arbitrary long height, IE 6 */
	background-position:top left;
}

.sort .b {
	/* bottom */
	position:relative;
	width:100%;
}

.sort .b,
.sort .b div {
	height:6px; /* height of bottom round */
	font-size:1px;
}

.sort .b {
	background-position:bottom right;
}

.sort .b div {
	position:relative;
	width:12px; /* bottom corner width */
	margin-left:-12px;
	background-position:bottom left;
}

.sort p {
	margin:0px;
	padding:0.5em 0px 0.5em 0px;
	line-height: 1.4em;
	color:#555;
}

.editpanel {
	background: #cccccc;
	height: 100px;
	display: none;
}
.button_editpanel {
	z-index:1;
	float:right;
	/* background: url(images/ todo - add arrow graphic ) no-repeat right -50px; */
	text-align: right;
	padding: 10px 0 0 0;
	cursor:pointer;
	color: #cccccc;
	text-decoration: none;
	outline: none;
	-moz-outline-style: none;
}
.active {
	/* background-position: right 12px;*/
	color: #666666;
}
a.togglepanel {
	color: #cccccc;
	cursor:pointer;
	text-decoration:none;
	float:right;
	padding: 9px 0 0 8px;
	font-weight: bold;
	outline: none;
	-moz-outline-style: none;
}