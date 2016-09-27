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
	border-top: 1px solid #CCC;
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
	border: 1px solid #ccc;
	color: #666;
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	width: 100%;	
	border-radius: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus,
input[type=number]:focus,
input[type=url]:focus,
textarea:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333;
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
	margin:5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin:0 3px 0 0;
	padding:0;
	border:none;
	border-radius:0;
	width:auto;
}
.elgg-input-checkbox + label,
.elgg-input-checkbox + .elgg-field-label {
	display: inline-block;
}
.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radios.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
}

.elgg-form-login, .elgg-form-account {
	max-width: 475px;
	margin: 0 auto;
}

/* ***************************************
	FRIENDS PICKER
*************************************** */

.elgg-friendspicker-list {
	max-height: 200px;
	overflow-y: auto;
	border: 1px solid #DCDCDC;
}
.elgg-friendspicker-list > .elgg-item {
	display: inline-block;
	vertical-align: top;
	border: none;
}
.elgg-friendspicker-list > .elgg-item label {
	display: block;
	margin: 5px;
}
.elgg-friendspicker-checkbox,
.elgg-friendspicker-icon,
.elgg-friendspicker-name {
	display: inline-block;
	line-height: 25px;
	vertical-align: middle;
	margin-right: 5px;
}
.elgg-friendspicker-name {
	width: 170px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.elgg-friendspicker-footer {
	display: block;
	margin: 15px 5px 5px;
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
	background-color: white;
	border: 1px solid #ccc;
	overflow: hidden;
	border-radius: 5px;
}
.ui-autocomplete .ui-menu-item {
	padding: 0px 4px;
	border-radius: 5px;
}
.ui-autocomplete .ui-menu-item:hover {
	background-color: #eee;
}
.ui-autocomplete a:hover {
	text-decoration: none;
	color: #4690D6;
}
.ui-autocomplete a.ui-state-hover {
	background-color: #eee;
	display: block;
}
.ui-helper-hidden-accessible {
	border: 0;
	clip: rect(0 0 0 0);
	height: 1px;
	margin: -1px;
	overflow: hidden;
	padding: 0;
	position: absolute;
	width: 1px;
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
	background-color: white;
	border: 1px solid #0054A7;
	border-radius: 6px;
	overflow: hidden;
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.ui-datepicker-inline {
	box-shadow: none;
	max-width: 225px;
}

.ui-datepicker-header {
	position: relative;
	background: #4690D6;
	color: white;
	padding: 2px 0;
	border-bottom: 1px solid #0054A7;
}
.ui-datepicker-header a {
	color: white;
}
.ui-datepicker-prev, .ui-datepicker-next {
	position: absolute;
	top: 5px;
	cursor: pointer;
}
.ui-datepicker-prev {
	left: 6px;
}
.ui-datepicker-next {
	right: 6px;
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
	border: 1px solid #ccc;
	color: #4690D6;;
	background: #fafafa;
}
.ui-datepicker-calendar .ui-state-hover {
	border: 1px solid #aaa;
	color: #0054A7;
	background: #eee;
}
.ui-datepicker-calendar .ui-state-active,
.ui-datepicker-calendar .ui-state-active.ui-state-hover {
	font-weight: bold;
	border: 1px solid #0054A7;
	color: #0054A7;
	background: #E4ECF5;
}
