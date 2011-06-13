<?php
/**
 * CSS form/input elements
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	Form Elements
*************************************** */
fieldset > div {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}
.elgg-form-footer {
}
.elgg-form-footer-alt {
	border-top: 1px solid #CCC;
	padding: 10px 0;
}

label {
	font-weight: bold;
	color: #333;
	font-size: 110%;
}

input, textarea {
	border: 1px solid #ccc;
	color: #666;
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	width: 100%;	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

input:focus, textarea:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333;
}

textarea {
	height: 200px;
}


.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}


.elgg-input-access {
	margin:5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin:0 3px 0 0;
	padding:0;
	border:none;
	width:auto;
}
.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radio.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
}

.elgg-form-account input[type="text"],
.elgg-form-account input[type="password"] {
	width: 300px;
}

/* ***************************************
	FRIENDS PICKER
*************************************** */
.friends-picker-main-wrapper {
	margin-bottom: 15px;
}
.friends-picker-container h3 {
	font-size:4em !important;
	text-align: left;
	margin:10px 0 20px !important;
	color:#999 !important;
	background: none !important;
	padding:0 !important;
}
.friends-picker .friends-picker-container .panel ul {
	text-align: left;
	margin: 0;
	padding:0;
}
.friends-picker-wrapper {
	margin: 0;
	padding:0;
	position: relative;
	width: 100%;
}
.friends-picker {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding:0;
	width: 730px;
	height: auto;
	background-color: #dedede;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
.friendspicker-savebuttons {
	background: white;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	margin:0 10px 10px;
}
.friends-picker .friends-picker-container { /* long container used to house end-to-end panels. Width is calculated in JS  */
	position: relative;
	left: 0;
	top: 0;
	width: 100%;
	list-style-type: none;
}
.friends-picker .friends-picker-container .panel {
	float:left;
	height: 100%;
	position: relative;
	width: 730px;
	margin: 0;
	padding:0;
}
.friends-picker .friends-picker-container .panel .wrapper {
	margin: 0;
	padding:4px 10px 10px 10px;
	min-height: 230px;
}
.friends-picker-navigation {
	margin: 0 0 10px;
	padding:0 0 10px;
	border-bottom:1px solid #ccc;
}
.friends-picker-navigation ul {
	list-style: none;
	padding-left: 0;
}
.friends-picker-navigation ul li {
	float: left;
	margin:0;
	background:white;
}
.friends-picker-navigation a {
	font-weight: bold;
	text-align: center;
	background: white;
	color: #999;
	text-decoration: none;
	display: block;
	padding: 0;
	width:20px;
	
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.tabHasContent {
	background: white;
	color:#333 !important;
}
.friends-picker-navigation li a:hover {
	background: #333;
	color:white !important;
}
.friends-picker-navigation li a.current {
	background: #4690D6;
	color:white !important;
}
.friends-picker-navigation-l, .friends-picker-navigation-r {
	position: absolute;
	top: 46px;
	text-indent: -9000em;
}
.friends-picker-navigation-l a, .friends-picker-navigation-r a {
	display: block;
	height: 40px;
	width: 40px;
}
.friends-picker-navigation-l {
	right: 48px;
	z-index:1;
}
.friends-picker-navigation-r {
	right: 0;
	z-index:1;
}
.friends-picker-navigation-l {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left top;
}
.friends-picker-navigation-r {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px top;
}
.friends-picker-navigation-l:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left -44px;
}
.friends-picker-navigation-r:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px -44px;
}
.friendspicker-savebuttons .elgg-button-submit,
.friendspicker-savebuttons .elgg-button-cancel {
	margin:5px 20px 5px 5px;
}
.friendspicker-members-table {
	background: #dedede;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	margin:10px 0 0;
	padding:10px 10px 0;
}

/* ***************************************
	USER PICKER
*************************************** */

.user-picker .user-picker-entry {
	clear:both;
	height:25px;
	padding:5px;
	margin-top:5px;
	border-bottom:1px solid #cccccc;
}
.user-picker-entry .elgg-button-delete {
	margin-right:10px;
}
/* ***************************************
        Datepicker
**************************************** */

#ui-datepicker-div, .ui-datepicker-inline, .ui-datepicker-calendar{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	padding: 0;
	margin: 0;
	background: #E4ECF5;
	width: 220px;
	color: black;
}
#ui-datepicker-div {
	display: none;
	border: 1px solid #777;
	z-index: 9999; /*must have*/
}
.ui-datepicker-inline {
	float: left;
	display: block;
	border: 0;
}
.ui-datepicker-rtl {
	direction: rtl;
}
.ui-datepicker-dialog {
	padding: 5px !important;
	border: 4px ridge #ddd !important;
}
button.ui-datepicker-trigger {
	width: 25px;
}
img.ui-datepicker-trigger {
	margin: 2px;
	vertical-align: middle;
}
.ui-datepicker-prompt {
	float: left;
	padding: 2px;
	background: #ddd;
	color: #000;
}
* html .ui-datepicker-prompt {
	width: 185px;
}
.ui-datepicker-control, .ui-datepicker-links, .ui-datepicker-header, .ui-datepicker {
	clear: both;
	float: left;
	width: 218px;
	color: #fff;
}
.ui-datepicker-control {
	background: #400;
	padding: 2px 0px;
}
.ui-datepicker-links {
	background: #000;
	padding: 2px 0px;
}
.ui-datepicker-control, .ui-datepicker-links {
	font-weight: bold;
	font-size: 80%;
}
.ui-datepicker-links label { /* disabled links */
	padding: 2px 5px;
	color: #888;
}
.ui-datepicker-clear, .ui-datepicker-prev {
	float: left;
}
.ui-datepicker-rtl .ui-datepicker-clear, .ui-datepicker-rtl .ui-datepicker-prev {
	float: right;
	text-align: right;
}
.ui-datepicker-current {
	float: left;
	width: 30%;
	text-align: center;
}
.ui-datepicker-close, .ui-datepicker-next {
	float: right;
	text-align: right;
	padding: 0px 0px 2px 0px;
}
.ui-datepicker-rtl .ui-datepicker-close, .ui-datepicker-rtl {
	float: left;
	text-align: left;
}
.ui-datepicker-header {
	padding: 1px 0 3px;
	background: #4690D6;
	text-align: center;
	font-weight: bold;
	height: 1.3em;
	padding: 0 2px 3px 0;
}
.ui-datepicker-header select {
	background: #333;
	color: #fff;
	border: 0px;
	font-weight: bold;
}
.ui-datepicker {
	background: #ccc;
	text-align: center;
	font-size: 100%;
}
.ui-datepicker a {
	display: block;
	width: 30px;
}
.ui-datepicker-title-row {
	background: #777;
}
.ui-datepicker-days-row {
	background: #eee;
	color: #666;
}
.ui-datepicker-week-col {
	background: #777;
	color: #fff;
}
.ui-datepicker-days-cell {
	color: #000;
	border: 1px solid #ddd;
}
.ui-datepicker-days-cell a{
	display: block;
}
.ui-datepicker-week-end-cell {
	background: #ddd;
}
.ui-datepicker-title-row .ui-datepicker-week-end-cell {
	background: #777;
}
.ui-datepicker-days-cell-over {
	background: #fff;
	border: 1px solid #777;
}
.ui-datepicker-unselectable {
	color: #E4ECF5;
}
.ui-datepicker-today {
	background: #4690D6 !important;
}
.ui-datepicker-current-day {
	background: #999 !important;
}
.ui-datepicker-status {
	background: #ddd;
	width: 100%;
	font-size: 80%;
	text-align: center;
}
        
/* ________ Datepicker Links _______
        
** Reset link properties and then override them with !important */
#ui-datepicker-div a, .ui-datepicker-inline a {
	cursor: pointer;
	margin: 0;
	padding: 0;
	background: none;
	color: #000;
	align: center !important;
}
.ui-datepicker-inline .ui-datepicker-links a {
	padding: 0 5px !important;
}
.ui-datepicker-control a, .ui-datepicker-links a {
	padding: 2px 5px !important;
	color: #eee !important;
}
.ui-datepicker-title-row a {
	color: #eee !important;
}
.ui-datepicker-control a:hover {
	background: #fdd !important;
	color: #333 !important;
}
.ui-datepicker-links a:hover, .ui-datepicker-title-row a:hover {
	background: #ddd !important;
	color: #333 !important;
}
        
/* ___________ MULTIPLE MONTHS _________*/
        
.ui-datepicker-multi .ui-datepicker {
	border: 1px solid #777;
}
.ui-datepicker-one-month {
	float: left;
	width: 185px;
}
.ui-datepicker-new-row {
	clear: left;
}
        
/* ___________ IE6 IFRAME FIX ________ */
        
.ui-datepicker-cover {
	display: none; /*sorry for IE5*/
	display/**/: block; /*sorry for IE5*/
	position: absolute; /*must have*/
	z-index: -1; /*must have*/
	filter: mask(); /*must have*/
	top: -4px; /*must have*/
	left: -4px; /*must have*/
	width: 200px; /*must have*/
	height: 200px; /*must have*/
}

