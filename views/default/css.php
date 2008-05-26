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

<?php
  include("reset.css");
?>


body {
	text-align:left;
	margin:0 auto;
	padding:0;
	background: #42afdc url(<?php echo $vars['url']; ?>/_graphics/pagebackground.gif) repeat-x;
	font: 75%/170% Arial, Helvetica, sans-serif;
	padding: 0px;
	margin: 0px;
	color: #333333;
}

/* ***************************************
	DEFAULT BASE STYLES
*************************************** */
a {
	color: #3399cc;
	text-decoration: none;
}
a:visited {
	color: #336699;
}
a:hover {
	text-decoration: underline;
}
p {
	margin: 0px 0px 15px;
}
img {
	border: none;
}
ul {
	margin: 5px 0px 15px;
	padding-left: 20px;
}
ul li {
	margin: 0px;
}
ol {
	margin: 5px 0px 15px;
	padding-left: 20px;
}
ul li {
	margin: 0px;
}
form {
	margin: 0px;
	padding: 0px;
}
small {
	font-size: 90%;
}

/* CONTAINER: WRAPS THE HEADER AND MAIN CONTENT AREA BELOW THE USER TOOLBAR */
div#container {
	width:998px;
	margin:0 auto;
	background: url(<?php echo $vars['url']; ?>/_graphics/pagebackground.jpg) no-repeat center top;
	min-height: 600px;
}

/* ***************************************
	HEADER
*************************************** */

div#header {
	margin:0 0 10px 0;
	text-align:left;
	position:relative;
	width:100%;
	height:60px;
	border-bottom:1px solid white;
}

div#header h1 {
	font: 160%/100% "Lucida Grande", Arial, sans-serif;
	padding:10px 0 0 0;
	color:#ffffff;
}

div#header h2 {
	font: 150%/100% "Lucida Grande", Arial, sans-serif;
	color:#ffffff;
}

div#header h1 a{
	color:#ffffff;
}


/* ***************************************
  Topmenu
*************************************** */

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

/* ***************************************
    sidebar menu
*************************************** */

#sidebar_menu {
	width: 150px;
	float: left;
	background: #ffffff;
}

#sidebar_right {
	width:380px;
	min-height: 380px;
	float:right;
	border-top:1px solid black;
	background: #ffffff;
}

#mainContent {
    margin:0 0 0 165px;
    padding:10px 20px 20px 20px;
    min-height: 480px;
    background: #ffffff;
}

#login-box {
    text-align:left;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
    background: #ffffff;
}

/* ***************************************
	FOOTER
*************************************** */

div#footer {
	clear: both;
	position: relative;
	font-size:1em;
	height:40px;
	width:998px;
	margin:0 auto;
	font-weight:bold;
	background: #ffffff;
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

/* ***************************************
  System messages
*************************************** */

.messages {
    border:1px solid #D3322A;
    background:#F7DAD8;
    color:#000;
    padding:3px 10px 3px 10px;
    margin:10px 0px 10px 0px;
}


/* ***************************************
	elgg toolbar
*************************************** */

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

.elggtoolbar li {
	background: #e4ecf5;
}

.elggtoolbar li a {
	text-decoration: none;
	color: #3399cc;
	line-height:0.5em;
}
.elggtoolbar li a:hover {
	text-decoration: underline;
	color: #003366;
}

.elggtoolbar h2.drawer-handle {
	margin: 0;
	padding: 1px 1px 1px 10px;
	background-color: #ffffff;
	border-bottom: 1px solid #0099cc;
	cursor: pointer;
	font-size: 100%;
}

.elggtoolbar h2.drawer-handle.open {
	color:#ffffff;
	background-color: #003366;
	border-bottom: 1px solid #003366;
}



/* ***************************************
	GENERAL FORM ELEMENTS
*************************************** */
label {
	font-weight: bold;
	color:#666;
	font-size: 100%;
}
input {
	font: 110% Arial, Helvetica, sans-serif;
	width: 220px;
	padding: 5px 3px 3px 7px;
	border: 1px solid #cccccc;
}
textarea {
	font: 110% Arial, Helvetica, sans-serif;
	width: 480px;
	height: 140px;
	border: solid 1px #cccccc;
	padding: 5px 3px 3px 7px;
}
.submit_button {
	font: 12px/100% Arial, Helvetica, sans-serif;
	color: #000000;
	border: none;
	width: 135px;
	height: 25px;
	padding-bottom: 6px;
	margin:10px 0 10px 0;
	cursor: pointer;
}
.submit_button:hover {
	background: #e4ecf5;
}
textarea:focus, input[type="text"]:focus {
	border: solid 1px #3b9acc;
	background: #e4ecf5;
}

.input-text,
.input-tags,
.input-url,
.input-textarea {
	width:95%;
}

.input-textarea {
	height: 200px;
}


/* ***************************************
	MAIN CONTENT ELEMENTS
*************************************** */
#mainContent h1 {
	color: #333333;
	margin: 3px 0 2px;
	padding-bottom: 4px;
	font: normal 160%/100% "Lucida Grande", Arial, sans-serif;
	border-bottom: dotted 1px #CCCCCC;
}
#mainContent h1 a, #mainContent h1 a:visited {
	color: #333333;
	text-decoration: none;
}
#mainContent h1 a:hover {
	color: #44a1d0;
	text-decoration: none;
}
#mainContent h2 {
	color: #333333;
	margin: 3px 0 2px;
	padding-bottom: 4px;
	font: normal 150%/100% "Lucida Grande", Arial, sans-serif;
	border-bottom: dotted 1px #CCCCCC;
}
#mainContent h2 a, #mainContent h2 a:visited {
	color: #333333;
	text-decoration: none;
}
#mainContent h2 a:hover {
	color: #44a1d0;
	text-decoration: none;
}
#mainContent h3 {
	color: #666666;
	margin: 5px 0px 5px;
	font: bold 110%/110% "Lucida Grande", Arial, sans-serif;
}


