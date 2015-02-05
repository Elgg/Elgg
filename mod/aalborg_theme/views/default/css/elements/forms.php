<?php
/**
 * CSS form/input elements
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	Form Elements
*************************************** */
fieldset > div {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}
.elgg-form-alt > fieldset > .elgg-foot {
	border-top: 1px solid #DCDCDC;
	padding: 10px 0;
}
label {
	font-weight: bold;
	color: #333;
	font-size: 110%;
}
label.elgg-state-disabled {
	opacity: 0.6;
}
input, textarea {
	border: 1px solid #DCDCDC;
	color: #666;
	font: 100% Arial, Helvetica, sans-serif;
	padding: 7px 6px;
	width: 100%;
	border-radius: 3px;

	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus,
input[type=url]:focus,
textarea:focus {
	border: solid 1px #C2C2C2;
	background: #F0F0F0;
	/* We remove outlines from specific input types so we can leave the browser
	   defaults (like glows) for everything else */
	outline: 0 none;
}
.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}
.elgg-input-access {
	margin: 5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin: 0 3px 0 0;
	padding: 0;
	border: none;
	width: auto;
}
.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radios.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
}
select {
	max-width: 100%;
	padding: 4px; 
}
.elgg-form-account {
	margin-bottom: 15px;
}
.elgg-form-login, .elgg-form-account {
	max-width: 475px;
	margin: 0 auto;
}

/* ***************************************
	FRIENDS PICKER
*************************************** */
.friends-picker-main-wrapper {
	margin-bottom: 15px;
}
.friends-picker-container h3 {
	font-size: 4em !important;
	text-align: left;
	margin: 10px 0 20px !important;
	color: #999 !important;
	background: none !important;
	padding:0 !important;
}
.friends-picker .friends-picker-container .panel ul {
	text-align: left;
	margin: 0;
	padding: 0;
}
.friends-picker-wrapper {
	margin: 0;
	padding: 0;
	position: relative;
	width: 720px;
}
.friends-picker {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding: 0;
	width: 720px;
	height: auto;
	background-color: #DEDEDE;
	border-radius: 3px;
}
.friendspicker-savebuttons {
	background: #FFF;
	border-radius: 3px;
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
	float: left;
	height: 100%;
	position: relative;
	width: 730px;
	margin: 0;
	padding: 0;
}
.friends-picker .friends-picker-container .panel .wrapper {
	margin: 0;
	padding: 4px 10px 10px 10px;
	min-height: 230px;
}
.friends-picker-navigation {
	margin: 10px 0;
	padding: 0 0 10px;
	border-bottom: 1px solid #DCDCDC;
}
.friends-picker-navigation ul {
	list-style: none;
	padding-left: 0;
}
.friends-picker-navigation ul li {
	float: left;
	margin: 0;
	background: #FFF;
}
.friends-picker-navigation a {
	font-weight: bold;
	text-align: center;
	background: #FFF;
	color: #999;
	text-decoration: none;
	display: block;
	padding: 0;
	width: 20px;
	border-radius: 3px;
}
.tabHasContent {
	background: #FFF;
	color: #333 !important;
}
.friends-picker-navigation li a:hover {
	background: #333;
	color: #FFF !important;
}
.friends-picker-navigation li a.current {
	background: #5097CF;
	color: #FFF !important;
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
	z-index: 1;
}
.friends-picker-navigation-r {
	right: 0;
	z-index: 1;
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
	margin: 5px 20px 5px 5px;
}
#friendspicker-members-table {
	margin: 10px 0 0;
	padding: 10px 10px 0;
}

/* ***************************************
	AUTOCOMPLETE
*************************************** */
<?php //autocomplete will expand to fullscreen without max-width ?>
.ui-autocomplete {
	position: absolute;
	cursor: default;
	z-index: 10000;
}
.elgg-autocomplete-item .elgg-body {
	max-width: 600px;
}
.ui-autocomplete {
	background-color: #FFF;
	border: 1px solid #DCDCDC;
	overflow: hidden;
	border-radius: 3px;
}
.ui-autocomplete .ui-menu-item {
	padding: 0px 4px;
	border-radius: 3px;
}
.ui-autocomplete .ui-menu-item:hover {
	background-color: #EEE;
}
.ui-autocomplete a:hover {
	text-decoration: none;
	color: #5097CF;
}
.ui-autocomplete a.ui-state-hover {
	background-color: #EEE;
	display: block;
}

/* ***************************************
	USER PICKER
*************************************** */
.elgg-user-picker-list li:first-child {
	border-top: 1px dotted #ccc;
	margin-top: 5px;
}
.elgg-user-picker-list > li {
	border-bottom: 1px dotted #ccc;
}
.elgg-user-picker.elgg-state-disabled > input,
.elgg-user-picker.elgg-state-disabled > label {
	display: none;
}
.elgg-user-picker-remove {
	cursor: pointer;
}

/* ***************************************
	DATE PICKER
**************************************** */
.ui-datepicker {
	display: none;

	margin-top: 3px;
	background-color: #FFF;
	border: 1px solid #0054A7;
	border-radius: 3px;
	overflow: hidden;
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.ui-datepicker-inline {
	box-shadow: none;
}

.ui-datepicker-header {
	position: relative;
	background: #5097CF;
	color: #FFF;
	padding: 2px 0;
	border-bottom: 1px solid #0054A7;
}
.ui-datepicker-header a {
	color: #FFF;
}
.ui-datepicker-prev, .ui-datepicker-next {
	position: absolute;
	top: 3px;
	cursor: pointer;
	border: 1px solid #fff;
	border-radius: 3px;
	padding: 1px 7px;
}
.ui-datepicker-prev:hover,
.ui-datepicker-next:hover {
	text-decoration: none;
}
.ui-datepicker-prev {
	left: 3px;
}
.ui-datepicker-next {
	right: 3px;
}
.ui-datepicker-title {
	line-height: 1.8em;
	margin: 0 30px;
	text-align: center;
	font-weight: bold;
}
.ui-datepicker-calendar {
	margin: 4px;
}
.ui-datepicker th {
	color: #0054A7;
	border: none;
	font-weight: bold;
	padding: 5px 6px;
	text-align: center;
}
.ui-datepicker td {
	padding: 1px;
}
.ui-datepicker td span, .ui-datepicker td a {
	display: block;
	padding: 2px;
	line-height: 1.2em;
	text-align: right;
	text-decoration: none;
}
.ui-datepicker-calendar .ui-state-default {
	border: 1px solid #DCDCDC;
	color: #5097CF;;
	background: #FAFAFA;
}
.ui-datepicker-calendar .ui-state-hover {
	border: 1px solid #AAA;
	color: #0054A7;
	background: #EEE;
}
.ui-datepicker-calendar .ui-state-active,
.ui-datepicker-calendar .ui-state-active.ui-state-hover {
	font-weight: bold;
	border: 1px solid #0054A7;
	color: #0054A7;
	background: #E4ECF5;
}

