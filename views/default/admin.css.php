<?php
/**
 * Elgg Admin CSS
 *
 * This is a distinct theme from the theme of the site. There are dependencies
 * on the HTML created by the views in Elgg core.
 *
 * @package Elgg.Core
 * @subpackage UI
 */

?>
/* <style> /**/

/* ***************************************
	RESET CSS
*************************************** */
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
}
<?php // force vertical scroll bar ?>
html, body {
	height: 100%;
	margin-bottom: 1px;
}
img {
	border-width: 0;
	border-color: transparent;
}
ol, ul {
	list-style: none;
}
em, i {
	font-style: italic;
}
ins {
	text-decoration: none;
}
del, strike {
	text-decoration:line-through;
}
strong, b {
	font-weight: bold;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
caption, th, td {
	text-align: left;
	font-weight: normal;
	vertical-align: top;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: "";
}
blockquote, q {
	quotes: "" "";
}

/* ***************************************
	BASICS
*************************************** */
body {
	background-color: #f5f5f5;
	font-size: 90%;
	line-height: 1.4em;
	font-family: "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color: #666;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; font-weight: 300; }
h3 { font-size: 1.2em; font-weight: 300; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }

a {
	color: #333;
	text-decoration: none;
}
a:hover {
	color: black;
	text-decoration: underline;
}
pre, code {
	background-color: #EEE;
	border: 1px solid #DDD;
	color: #444;
	font-family: Monaco, "Courier New", Courier, monospace;
	font-size: 13px;
	overflow: auto;
	margin: 15px 0;
	padding: 5px;
}
blockquote {
	background: #EBF5FF;
}
p {
	margin-bottom: 15px;
}

.clearfloat {
	clear: both;
}

/* Clearfix! */
.clearfix:after,
.elgg-grid:after,
.elgg-layout:after,
.elgg-inner:after,
.elgg-page-header:after,
.elgg-page-footer:after,
.elgg-head:after,
.elgg-foot:after,
.elgg-col:after,
.elgg-col-alt:after,
.elgg-image-block:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.elgg-body {
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
}
.elgg-body:after {
	display: block;
	visibility: hidden;
	height: 0 !important;
	line-height: 0;
	overflow: hidden;
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}

/* ***************************************
	PAGE SECTIONS
*************************************** */
.elgg-page-section {
	padding-left: 20px;
	padding-right: 20px;
}

.elgg-page-section > .elgg-inner {
	margin: 0 auto;
	min-width: 800px;
	max-width: 1600px;
}

/* ***************************************
	HEADER
*************************************** */
.elgg-page-header {
	background-color: #24292e;
	padding: 20px;
}
.elgg-heading-site {
	font-size: 1.8em;
	line-height: 1.2em;
	margin-right: 10px;
	float: left;
}
.elgg-heading-site a {
	color: #ffffff;
	text-decoration: none;
}
.elgg-heading-site a:hover {
	color: white;
	text-decoration: none;
}
.elgg-heading-site small {
	font-size: 65%;
	font-weight: 300;
}

.elgg-menu-user,
.elgg-menu-admin-header {
	float: right;
}
.elgg-menu-user,
.elgg-menu-admin-header,
.elgg-menu-user a,
.elgg-menu-admin-header a {
	color: rgba(255,255,255,0.75);
}
.elgg-menu-user a,
.elgg-menu-admin-header a {
	text-decoration: underline;
}
.elgg-menu-user a:hover,
.elgg-menu-admin-header a:hover {
	color: white;
}
.elgg-menu-user li,
.elgg-menu-admin-header li {
	display: inline;
}
.elgg-menu-user li:after,
.elgg-menu-admin-header li:after {
	content: "|";
	display: inline-block;
	font-weight: normal;
	margin: 0 8px;
}
.elgg-menu-user li:last-child:after,
.elgg-menu-admin-header li:last-child:after {
	content: "";
}
a.elgg-maintenance-mode-warning {
	color: #d00;
}
.elgg-menu-item-admin-profile .elgg-avatar {
	margin-left: 5px;
	vertical-align: sub;
}

<?= elgg_view('elements/components/messages.css') ?>

.elgg-system-messages {
	margin-top: 10px;
}

.elgg-admin-notices li {
	margin: 10px 0 0;
	border: none;
}

.elgg-system-messages .elgg-message {
	cursor: pointer;
}

/* ***************************************
	BODY
*************************************** */
.elgg-page-body {
	padding: 30px 0;
}
.elgg-main {
	background-color: #fff;
	padding: 20px;
	position: relative;
	min-height: 400px;
}
.elgg-sidebar {
	width: 210px;
	float: right;
	margin-left: 30px;
	box-sizing: border-box;
}
.elgg-main > .elgg-head {
	margin-bottom: 10px;
}
.elgg-main h2 {
	color: #333333;
}

/* ***************************************
	FOOTER
*************************************** */
.elgg-page-footer {
	background-color: #24292e;
	padding-top: 20px;
	padding-bottom: 20px;
}
.elgg-page-footer a {
	color: rgba(255,255,255,0.75);
	font-weight: bold;
	text-decoration: none;
}
.elgg-page-footer a:hover {
	text-decoration: underline;
}

/* ***************************************
	MODULES
*************************************** */
.elgg-module {
	overflow: hidden;
}
.elgg-module-main {
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 10px;
}
.elgg-module-main > .elgg-head {
	margin-bottom: 5px;
}
.elgg-module-info > .elgg-head {
	margin-bottom: 10px;
}
.elgg-module-inline {
	margin: 20px 0;
}
.elgg-module-inline > .elgg-head {
	background-color: #999;
	color: white;
	padding: 5px;
	margin-bottom: 10px;
	border-radius: 3px;
}
.elgg-module-inline > .elgg-head h3 {
	color: white;
}
.elgg-module-popup {
	background-color: white;
	border: 1px solid #ccc;
	z-index: 9999;
	margin-bottom: 0;
	padding: 5px;
	border-radius: 6px;
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.elgg-module-popup > .elgg-head {
	margin-bottom: 5px;
}
.elgg-module-popup > .elgg-head * {
	color: #666;
}
.elgg-module-featured {
	border: 1px solid #666;
	border-radius: 6px;
}
.elgg-module-featured > .elgg-head {
	padding: 5px;
	background-color: #333;
}
.elgg-module-featured > .elgg-head * {
	color: white;
}
.elgg-module-featured > .elgg-body {
	padding: 10px;
}

/* ***************************************
	TABLE
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table td, .elgg-table th {
	background: white;
	border: 1px solid #ccc;
	padding: 4px 8px;
	vertical-align: middle;
}
.elgg-table th {
	background-color: #ddd;
}
.elgg-table .alt td {
	background: #eee;
}
.elgg-table input[type=checkbox] {
	margin-top: 3px;
}

.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table-alt th {
	background-color: #eee;
	font-weight: bold;
}
.elgg-table-alt td, .elgg-table-alt th {
	padding: 2px 4px;
	border-bottom: 1px solid #ccc;
}
.elgg-table-alt td:first-child {
	width: 200px;
}
.elgg-table-alt tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	LISTS AND IMAGE BLOCK
*************************************** */
.elgg-image-block {
	padding: 3px 0;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 5px;
}
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 5px;
}
.elgg-item {
	margin: 3px;
}
.elgg-list-simple li {
	margin-bottom: 5px;
}
.elgg-list-distinct {
	border-top: 1px dotted #CCCCCC;
	margin: 5px 0;
	clear: both;
}
.elgg-list-distinct > li {
	border-bottom: 1px dotted #CCCCCC;
}

.elgg-gallery > li {
	position: relative;
	display: inline-block;
}

<?= elgg_view('elements/components/tags.css', $vars) ?>

/* ***************************************
	FORMS AND INPUT
*************************************** */
label {
	font-weight: bold;
	color: #333333;
	font-size: 110%;
}
label.elgg-state-disabled,
input.elgg-state-disabled,
.elgg-field-label.elgg-state-disabled {
	opacity: 0.6;
}
.elgg-field-label {
	display: block;
}
.elgg-required-indicator {
	font-size: 110%;
	font-weight: bold;
	color: #C24000;
	display: inline;
	padding: 0 5px;
}

fieldset > div, .elgg-field {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}
input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #ccc;
	color: #666;
	border-radius: 5px;
	margin: 0;
	box-sizing: border-box;
}

/* default elgg core input field classes */
.elgg-input-text,
.elgg-input-number,
.elgg-input-tags,
.elgg-input-url,
.elgg-input-email,
.elgg-input-password,
.elgg-input-plaintext,
.elgg-input-longtext {
	width: 100%;
}

.elgg-input-text,
.elgg-input-number,
.elgg-input-tags,
.elgg-input-url,
.elgg-input-email,
.elgg-input-password {
	max-width: 400px;
}
.elgg-input-thin {
	width: 400px;
}
.elgg-input-natural {
	width: auto;
}

input[type="radio"] {
	margin: 0 3px 0 0;
}

input[type="number"] {
	-moz-appearance: textfield;
}

select {
	max-width: 100%;
	padding: 4px;
}

.elgg-fieldset-has-legend {
	border: 1px solid #ccc;
	border-radius: 5px;
	padding: 5px;
	padding-bottom: 10px;
	margin-bottom: 15px;
}
.elgg-fieldset-has-legend > legend {
	color: #333333;
	font-size: 110%;
	font-weight: bold;
	padding: 0 3px;
}
.elgg-fieldset-horizontal .elgg-field {
	display: inline-block;
	margin: 0 10px 0 0;
}

.elgg-fieldset-horizontal.elgg-justify-right .elgg-field {
	margin: 0 0 0 10px;
}

.elgg-fieldset-horizontal.elgg-justify-center .elgg-field {
	margin: 0 5px;
}

.elgg-button {
	display: inline-block;
	font-size: 100%;
	text-decoration: none;
	border-radius: 3px;
	width: auto;
	padding: 6px 12px;
	margin-bottom: 5px;
	cursor: pointer;
	box-sizing: border-box;
}

.elgg-button + .elgg-button {
	margin-left: 5px;
}

.elgg-button-submit,
.elgg-button-action {
	color: white;
	border: 1px solid #333;
	background-color: #333;
}
.elgg-button-submit:hover,
.elgg-button-action:hover {
	color: white;
	background-color: #000;
	text-decoration: none;
}
.elgg-button-cancel {
	color: #333;
	background-color: #ccc;
	border: 1px solid #999;
}
.elgg-button-cancel:hover {
	color: #222;
	background-color: #999;
	text-decoration: none;
}
.elgg-button-delete {
	border: 1px solid #FF3300;
	background: #FF3300;
	color: #FFF;
}
.elgg-button-delete:hover,
.elgg-button-delete:focus {
	background: #D63006;
	text-decoration: none;
	color: #FFF;
}
.elgg-button:disabled,
.elgg-button.elgg-state-disabled {
	background: #dedede;
	color: #888;
	cursor: default;
	cursor: not-allowed;
	border-color: #adadad;
}

.elgg-form-settings {
	max-width: 800px;
}

.elgg-input-checkbox + label,
.elgg-input-checkbox + .elgg-field-label {
	display: inline-block;
}
<?php
echo elgg_view('elements/misc/checkbox_switch.css');
?>

/* **************************************
	 DATE PICKER
*************************************** */
.ui-datepicker {
	margin-top: 3px;
	padding: 3px 3px 0;
	border: 1px solid #ccc;
	background-color: white;
}
.ui-datepicker-header {
	padding: 2px 0;
	border: 1px solid #ccc;
	background-color: #eee;
	border-radius: 5px;
}
.ui-datepicker-prev, .ui-datepicker-next {
	position: absolute;
	top: 9px;
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
	margin-bottom: 2px;
}
.ui-datepicker th {
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
	color: #555;
	background: #fafafa;
}
.ui-datepicker-calendar .ui-state-hover {
	border: 1px solid #aaa;
	color: #333;
	background: #ccc;
}
.ui-datepicker-calendar .ui-state-active,
.ui-datepicker-calendar .ui-state-active.ui-state-hover {
	font-weight: bold;
	border: 1px solid #999;
	color: #333;
	background: #ddd;
}

.ui-datepicker-inline {
	max-width: 225px;
}

/* ***************************************
	AUTOCOMPLETE
*************************************** */
<?php //autocomplete will expand to fullscreen without max-width ?>
.ui-autocomplete {
	position: absolute;
	cursor: default;
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
	  PROGRESS BAR
**************************************** */
.elgg-progressbar {
	height: 20px;
	border: 1px solid #CCC;
}
.ui-progressbar-value {
	height: 20px;
	background: green;
}
.elgg-progressbar-counter {
	float: left;
	color: white;
	margin: 1px;
}

/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 10px 0;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	margin: 0 6px 0 0;
	text-align: center;
}
.elgg-pagination span {
	padding: 2px 6px;
	color: #333;
	border: 1px solid #333;
	font-size: 12px;
	text-decoration: none;
}
.elgg-pagination a:hover span {
	background: #333;
	color: white;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled span {
	color: #CCC;
	border-color: #CCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #000;
	border-color: #ccc;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	margin-bottom: 5px;
	border-bottom: 1px solid #ccc;
	display: table;
	width: 100%;
}
.elgg-tabs li {
	float: left;
	border: 1px solid #ccc;
	border-bottom-width: 0;
	background: #eee;
	margin: 0 0 0 10px;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0 10px;
	text-align: center;
	height: 21px;
	color: #999;
}
.elgg-tabs a:hover {
	background: #dedede;
	color:#333;
}
.elgg-tabs .elgg-state-selected {
	border-color: #ccc;
	background: white;
}
.elgg-tabs .elgg-state-selected a {
	position: relative;
	top: 2px;
	background: white;
}

/* ***************************************
	SIDEBAR MENU
*************************************** */
.elgg-admin-sidebar-menu a {
	display: block;
	padding: 5px;
	color: #333;
	background: white;
	cursor: pointer;
	text-decoration: none;
	margin-bottom: 3px;
}
.elgg-admin-sidebar-menu a:hover {
	text-decoration: none;
	background: black;
	color: white;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > a {
	color: #fff;
	background-color: #BBB;
	border-color: #BBB;
}
.elgg-admin-sidebar-menu .elgg-menu-closed:before {
	content: "\25B8";
	padding-right: 4px;
}
.elgg-admin-sidebar-menu .elgg-menu-opened:before {
	content: "\25BE";
	padding-right: 4px;
}
.elgg-admin-sidebar-menu .elgg-child-menu {
	display: none;
	padding-left: 30px;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > ul {
	display: block;
}
.elgg-admin-sidebar-menu h2 {
	padding-bottom: 10px;
}
.elgg-admin-sidebar-menu ul.elgg-menu-page {
	padding-bottom: 15px;
}
.elgg-admin-sidebar-menu a:not(.elgg-menu-parent):before {
	content: "";
	padding-left: .65em;
}

/* ***************************************
	TITLE MENU
*************************************** */
.elgg-menu-title {
	float: right;
}
.elgg-menu-title > li {
	display: inline-block;
	margin-left: 4px;
}

/* ***************************************
	FOOTER MENU
*************************************** */
.elgg-menu-footer {
	color: gray;
}
.elgg-menu-footer li {
	float: left;
}
.elgg-menu-footer li:after {
	content: "\007C";
	display: inline-block;
	padding: 0 4px 0 4px;
	font-weight: normal;
}
.elgg-menu-footer li:last-child:after {
	content: "";
}
.elgg-menu-admin-footer a {
	font-weight: 300;
}

/* ***************************************
	GENERAL MENU
*************************************** */
.elgg-menu-general > li,
.elgg-menu-general > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-general > li:after {
	content: "\007C";
	padding: 0 4px;
}

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;
	overflow: hidden;
	border: 1px solid #DEDEDE;
	background-color: #FFF;
	margin-right: 10px;

	border-radius: 0 3px 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}

.elgg-menu-hover-card-container {
	display: flex;
	flex-wrap: wrap;
	max-width: 500px;
}

.elgg-menu-hover-card {
	padding: 8px 16px;
	min-width: 300px;
	flex: 2;
}

.elgg-menu-hover .elgg-menu > li a {
	padding: 8px 16px;
	color: #666;
}
.elgg-menu-hover .elgg-anchor-icon + .elgg-anchor-label {
	margin-left: 12px;
}
.elgg-menu-hover .elgg-menu a:hover {
	background-color: #F0F0F0;
	text-decoration: none;
}
.elgg-menu-hover-actions,
.elgg-menu-hover-default {
	border-left: 1px solid #efefef;
	flex: 1;
	white-space: nowrap;
}

.elgg-menu-hover-admin {
	border-top: 1px solid #efefef;
}

.elgg-menu-hover .elgg-menu-hover-admin a:hover {
	color: #FFF;
	background-color: #FF0000;
}

/* admin differences */
.elgg-menu-hover .elgg-menu > li a {
	display: block;
}

/* ***************************************
	ENTITY MENU
*************************************** */
<?php // height depends on line height/font size ?>
.elgg-menu-entity, .elgg-menu-annotation {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #666;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-entity > li, .elgg-menu-annotation > li {
	margin-left: 15px;
}
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	color: #aaa;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	display: block;
}
.elgg-menu-entity > li > span, .elgg-menu-annotation > li > span {
	vertical-align: baseline;
}

/* ***************************************
	WIDGET MENU
*************************************** */
.elgg-menu-widget-container {
	float: right;
	margin-right: 15px;
}
.elgg-menu-widget > li {
	display: inline-block;
	margin-left: 10px;
}

/* ***************************************
	MORE MENUS
*************************************** */
/* Horizontal menus w/ separator support */
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a {
	display: inline-block;
	vertical-align: middle;
}
/* Allow inline image blocks in horizontal menus */
.elgg-menu-hz .elgg-body:after {
	content: '.';
}
.elgg-menu > li:last-child::after {
	display: none;
}
.elgg-menu-admin-footer a {
	color: #eee;
}
.elgg-menu-admin-footer > li {
	padding-right: 25px;
}
.elgg-menu-longtext {
	float: right;
}
.elgg-menu-metadata {
	list-style-type: none;
	float: right;
	margin-left: 15px;
	font-size: 90%;
}
.elgg-menu-metadata > li {
	float: left;
	margin-left: 15px;
}
.elgg-menu-metadata, .elgg-menu-metadata a {
	color: #aaa;
}

.elgg-layout-widgets > .elgg-widgets {
	float: right;
}
.elgg-module-widget > .elgg-head h3 {
	font-weight: 600;
}

<?= elgg_view('elements/widgets.css', $vars) ?>

/* ***************************************
	GRID
*************************************** */
.elgg-grid {}
.elgg-col {
	float: left;
}
.elgg-col-alt {
	float: right;
}
.elgg-col-1of1 {
	float: none;
}
.elgg-col-1of2 {
	width: 50%;
}
.elgg-col-1of3 {
	width: 33.33%;
}
.elgg-col-2of3 {
	width: 66.66%;
}
.elgg-col-1of4 {
	width: 25%;
}
.elgg-col-3of4 {
	width: 75%;
}
.elgg-col-1of5 {
	width: 20%;
}
.elgg-col-2of5 {
	width: 40%;
}
.elgg-col-3of5 {
	width: 60%;
}
.elgg-col-4of5 {
	width: 80%;
}
.elgg-col-1of6 {
	width: 16.66%;
}
.elgg-col-5of6 {
	width: 83.33%;
}

<?= elgg_view('elements/icons.css', $vars) ?>

/* ***************************************
	PLUGINS
**************************************** */
#plugins-filter {
	margin: 5px 5px 20px;
}
.elgg-admin-plugins-categories li {
	padding-right: 5px;
}
.elgg-plugin {
	border: 1px solid #CCC;
	padding: 0 5px;
	border-radius: 5px;
}
.elgg-plugin:hover {
	border-color: #999;
}
.elgg-plugin .elgg-head {
	white-space: nowrap;
	overflow: hidden;
	max-width: 100%;
}
.elgg-plugin.elgg-state-draggable > .elgg-image-block .elgg-head {
	cursor: move;
}
.elgg-plugin > .elgg-image-block > .elgg-image {
	margin-right: 0;
	min-width: 9em;
	text-align: center;
}
.elgg-plugin > .elgg-image-block > .elgg-image .elgg-button {
	display: block;
	margin: 0;
	padding: 3px 10px;
	margin: 0;
}
.elgg-plugin > .elgg-image-block > .elgg-body {
	padding: 3px 10px;
}

.elgg-plugin p {
	margin: 0;
}
.elgg-plugin h3 {
	color: black;
	padding-bottom: 10px;
}
.elgg-plugin-list-description {
	display: inline-block;
	color: #999;
	margin-left: 5px;
}

.elgg-plugin.elgg-state-active,
.elgg-state-active .elgg-plugin-list-reordering {
	background: #fff;
}
.elgg-plugin.elgg-state-inactive,
.elgg-state-inactive .elgg-plugin-list-reordering {
	background: #eee;
}

.elgg-plugin.elgg-state-cannot-activate,
.elgg-plugin.elgg-state-cannot-activate .elgg-plugin-list-reordering {
	background: #f7f0d4;
}
.elgg-state-cannot-activate .elgg-image a[disabled],
.elgg-state-cannot-deactivate .elgg-image a[disabled] {
	text-decoration: none;
}

.elgg-plugin-list-reordering {
	float: right;
	display: none;
	position: relative;
}
.elgg-plugin:hover .elgg-plugin-list-reordering {
	display: block;
}

.elgg-plugin-list-reordering li {
	float:left;
	margin-left: 5px;
}

#elgg-plugin-list-cover {
	display: none;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: white;
	opacity: 0.5;
}

.elgg-plugin-settings {
	font-weight: normal;
	font-size: 0.9em;
	margin-left: 5px;
}
.elgg-plugin-contributors {
	list-style-position: inside;
	list-style-type: circle;
}
.elgg-plugin-contributors li {
	font-style: italic;
}
.elgg-plugin-contributors dl,
.elgg-plugin-contributors dd {
	display: inline;
	padding-right: 5px
}
.elgg-plugin-contributors dt {
	display: none;
}
.elgg-plugin-contributors dd:after {
	content: ', ';
}
.elgg-plugin-contributors dd.elgg-plugin-contributor-name:after {
	content: ' - ';
}
.elgg-plugin-contributors dd.elgg-plugin-contributor-description:after {
	content: '';
}
.elgg-plugin .elgg-state-error {
	background: #fbe3e4;
	color: #8a1f11;
	border-color: #fbc2c4;
	padding: 3px 6px;
	margin: 3px 0;
	width: auto;
}
.elgg-plugin .elgg-state-warning {
	background: #f4f4f4;
	color: #000000;
	border-color: #fbe58b;
	padding: 3px 6px;
	margin: 3px 0;
	width: auto;
}

#elgg-plugin-list {
	.elgg-plugin {
		.elgg-state-error, .elgg-state-warning {
			display: inline-block;
		}
	}
}

.elgg-plugin .elgg-state-error a,
.elgg-plugin .elgg-state-warning a,
.elgg-plugin .elgg-text-help a {
	text-decoration: underline;
}

.elgg-plugin-title {
	font-weight: bold;
}

.elgg-state-inactive .elgg-plugin-title {
	color: #666;
}

ul.elgg-plugin-categories, ul.elgg-plugin-categories > li,
ul.elgg-plugin-resources, ul.elgg-plugin-resources > li {
	display: inline;
}

.elgg-module-plugin-details .elgg-plugin {
	border: none;
	margin: 0;
	padding: 0;
}

.elgg-module-plugin-details {
	width: 600px;
	min-height: 500px;
}

.elgg-module-plugin-details .elgg-tabs a {
	cursor: pointer;
}

.elgg-plugin-details-screenshots > ul {
	text-align: center;
}

.elgg-plugin-details-screenshots > div {
	text-align: center;
}

.elgg-plugin-details-screenshots > div > img {
	max-height: 380px;
	max-width: 480px;
}

.elgg-plugin-details-screenshots > div > img.elgg-state-selected {
	display: inline-block;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot {
	display: inline;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot img {
	height: 50px;
	border: 1px solid #ccc;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot.elgg-state-selected img {
	border: 1px solid #999;
}

/****************************************
	MARKDOWN
****************************************/
.elgg-markdown {
	margin: 15px;
}
.elgg-markdown h1,
.elgg-markdown h2,
.elgg-markdown h3,
.elgg-markdown h4,
.elgg-markdown h5,
.elgg-markdown h6 {
	margin: 1em 0 1em -15px;
	color: #333;
}
.elgg-markdown ol {
	list-style: decimal;
	padding-left: 2em;
}
.elgg-markdown ul {
	list-style: disc;
	padding-left: 2em;
}
.elgg-markdown p {
	margin: 15px 0;
}
.elgg-markdown img {
	max-width: 100%;
	height: auto;
	margin: 10px 0;
}
.elgg-markdown pre > code {
	border: none;
}

/* ***************************************
	MISC
*************************************** */
.elgg-content-thin {
	max-width: 600px;
}

.elgg-subtext {
	color: #666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
	margin-bottom: 5px;
}

.elgg-subtext time {
	color: #666;
}

.elgg-text-help {
	display: block;
	font-size: 85%;
	font-style: italic;
}

.elgg-longtext-control {
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}

table.mceLayout {
	width:100% !important;
}

.elgg-output dt {
	font-weight: bold;
}
.elgg-output dd {
	margin: 0 0 1em 2em;
}

<?php echo elgg_view('elements/misc/spinner.css') ?>

/* ***************************************
	HELPERS
*************************************** */
.hidden,
.elgg-page .hidden {
	display: none;
}
.centered {
	margin: 0 auto;
}
.center {
	text-align: center;
}
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
.elgg-toggle {
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
	border: 1px solid #eeeeee;
}
.elgg-border-transition {
	border: 1px solid #eeeeee;
}
.elgg-divide-top {
	border-top: 1px solid #CCCCCC;
}
.elgg-divide-bottom {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-divide-left {
	border-left: 1px solid #CCCCCC;
}
.elgg-divide-right {
	border-right: 1px solid #CCCCCC;
}

/* ***************************************
	SPACING (from OOCSS)
*************************************** */
.pan{padding:0}
.pas{padding:5px}
.pam{padding:10px}
.pal{padding:20px}
.ptn{padding-top:0}
.pts{padding-top:5px}
.ptm{padding-top:10px}
.ptl{padding-top:20px}
.prn{padding-right:0}
.prs{padding-right:5px}
.prm{padding-right:10px}
.prl{padding-right:20px}
.pbn{padding-bottom:0}
.pbs{padding-bottom:5px}
.pbm{padding-bottom:10px}
.pbl{padding-bottom:20px}
.pln{padding-left:0}
.pls{padding-left:5px}
.plm{padding-left:10px}
.pll{padding-left:20px}
.phn{padding-left:0;padding-right:0}
.phs{padding-left:5px;padding-right:5px}
.phm{padding-left:10px;padding-right:10px}
.phl{padding-left:20px;padding-right:20px}
.pvn{padding-top:0;padding-bottom:0}
.pvs{padding-top:5px;padding-bottom:5px}
.pvm{padding-top:10px;padding-bottom:10px}
.pvl{padding-top:20px;padding-bottom:20px}
.man{margin:0}
.mas{margin:5px}
.mam{margin:10px}
.mal{margin:20px}
.mtn{margin-top:0}
.mts{margin-top:5px}
.mtm{margin-top:10px}
.mtl{margin-top:20px}
.mrn{margin-right:0}
.mrs{margin-right:5px}
.mrm{margin-right:10px}
.mrl{margin-right:20px}
.mbn{margin-bottom:0}
.mbs{margin-bottom:5px}
.mbm{margin-bottom:10px}
.mbl{margin-bottom:20px}
.mln{margin-left:0}
.mls{margin-left:5px}
.mlm{margin-left:10px}
.mll{margin-left:20px}
.mhn{margin-left:0;margin-right:0}
.mhs{margin-left:5px;margin-right:5px}
.mhm{margin-left:10px;margin-right:10px}
.mhl{margin-left:20px;margin-right:20px}
.mvn{margin-top:0;margin-bottom:0}
.mvs{margin-top:5px;margin-bottom:5px}
.mvm{margin-top:10px;margin-bottom:10px}
.mvl{margin-top:20px;margin-bottom:20px}

/* ***************************************
	RESPONSIVE
*************************************** */

html {
	font-size: 100%;
	-webkit-text-size-adjust: 100%;
	-ms-text-size-adjust: 100%;
}
.elgg-admin-button-nav {
	display: none;
	float: right;
	margin: 6px 0 0 10px;
	cursor: pointer;
}
.elgg-admin-button-nav:hover .icon-bar {
	background-color: #999;
}
.elgg-admin-button-nav .icon-bar {
	background-color: #F5F5F5;
	border-radius: 1px 1px 1px 1px;
	display: block;
	height: 3px;
	width: 26px;
}
.elgg-admin-button-nav .icon-bar + .icon-bar {
	margin-top: 5px;
}
@media (max-width: 1030px) {
	.elgg-page-section > .elgg-inner {
		min-width: 0;
	}
	.elgg-col-1of2 {
		width: 100%;
		min-height: 0 !important;
	}
}
@media (min-width: 769px) {
	#elgg-admin-nav-collapse {
		display: block !important;
	}
}
@media (max-width: 768px) {
	.elgg-admin-button-nav {
		display: block;
	}
	.elgg-heading-site {
		display: none;
	}
	#elgg-admin-nav-collapse {
		display: none;
		padding: 0 10px;
	}
	.elgg-sidebar {
		position: static;
		z-index: 100;
		left: 0;
		top: 0;
		width: auto;
		float: none;
		margin: 0;
	}
	.elgg-module-main > .elgg-body {
		padding: 30px;
	}
}
