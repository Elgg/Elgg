/* <style> /**/
/**
 * CSS form/input elements
 */

/* ***************************************
	Form Elements
*************************************** */
.elgg-required-indicator {
	font-size: 110%;
	font-weight: bold;
	color: #C24000;
	display: inline;
	padding: 0 5px;
}

.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}

<?php
echo elgg_view('elements/misc/checkbox_switch.css');
?>

.elgg-login-box, .elgg-register-box {
	max-width: 475px;
	margin: 0 auto;
}

.elgg-form-login, .elgg-form-account {
	max-width: 475px;
	margin: 0 auto;
}

.elgg-fieldset-horizontal .elgg-field {
	margin: 0 10px 0 0;
}

.elgg-fieldset-horizontal.elgg-justify-right .elgg-field {
	margin: 0 0 0 10px;
}

.elgg-fieldset-horizontal.elgg-justify-center .elgg-field {
	margin: 0 5px;
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
	border: 1px solid #ddd;
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
.elgg-user-picker-list > li:first-child {
	border-top: 1px solid #ccc;
	margin-top: 5px;
}
.elgg-user-picker-list > li {
	padding: 0 10px;
	border-bottom: 1px solid #ccc;
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
	max-width: 225px;
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
	border: 1px solid #ddd;
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



.elgg-output-field-label {
    margin: 0 0 5px;
    font-size: 90%;
}

.elgg-output-field-value {
    display: block;
    width: 100%;
    overflow: hidden;
}
