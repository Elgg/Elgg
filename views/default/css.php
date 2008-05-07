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

body {
    background:#fff url(<?php echo $vars['url']; ?>/_graphics/header.gif) repeat-x; /* #d9e2f2  */
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

ol, ul {
	color:#697C83;
}

h1 {
	font-size:130%;
}

h2, h3, h4, h5 {
	font-size:100%;
}

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
	margin:0;
	padding:0;
	text-align:left;
	position:relative;
	background:transparent;
	width:100%;
	height:120px;
}

div#header h1 {
	font-size:30px;
	padding:20px 0 0 0;
	margin:10px 0 6px 0;
}

div#header h2 {
	font-size:20px;
	padding:20px 0 0 0;
	margin:10px 0 6px 0;
	color: #fff;
}

div#header h1 a{
	color:#fff;
	font-family:Helvetica, "Myriad Web", Arial, sans-serif;
}

/**** End header ****/

/*
  Topmenu
*/

#topmenu {
    position:absolute;
    top:2px;
    right:10px;
    width: 300px;
}

#topmenu li {
    display:inline;
    list-style:none;
}

#topmenu li a {
    color:#fff;
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

#mainContent {
    margin:0 0 0 180px;
    width:680px;
    padding:20px;
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
	width: 600px;
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




