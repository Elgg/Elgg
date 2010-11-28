<?php
/**
 * Elgg Admin CSS
 */
?>

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
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
}
img {
	border-width:0;
	border-color:transparent;
}
:focus {
	outline:0 none;
	-moz-outline-style: none;
}
ol, ul {
	/* list-style:none outside none; */
	margin: 0 0 10px 0;
	padding-left: 20px;
}
em, i {
	font-style:italic;
}
ins {
	text-decoration:none;
}
del {
	text-decoration:line-through;
}
strong {
	font-weight:bold;
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
	text-align:left;
	margin:0 auto;
	padding:0;
	background-color: #444444;
	font-size: 80%;
	line-height: 1.4em;
	font-family: "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}
a {
	color:#0054A7;
	text-decoration: none;
	-moz-outline-style: none;
	outline: none;
}
a:hover,
a.selected {
	color: black;
	text-decoration: underline;
}
p {
	margin-bottom:15px;
}
p:last-child {
	margin-bottom:0;
}
small {
	font-size: 90%;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	line-height: auto;
	color:#0054A7;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }
dt {
	font-weight: bold;
}
dd {
	margin: 0 0 1em 1em;
}
pre, code {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:12px;
	background:#EBF5FF;
	overflow:auto;

	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
}
code {
	padding:2px 3px;
}
pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}
blockquote {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
	background:#EBF5FF;
	border:none;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}


/* ***************************************
	GENERIC SELECTORS
*************************************** */
h2 {
	border-bottom:1px solid #CCCCCC;
	padding-bottom:5px;
}
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}
.link {
	cursor:pointer;
}
.small {
	font-size: 90%;
}
.divider {
	border-top:1px solid #cccccc;
}
.hidden {
	display:none;
}
.radius8 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
.margin_none {
	margin:0;
}
.margin_top {
	margin-top:10px;
}
.entity_title a { color:#0054A7; }
.elgg_horizontal_tabbed_nav a:hover { color:#0054A7; }
table.mceLayout {
	width:100% !important;
}







#admin_header {
	background-color:#333333;
	border-bottom:1px solid #555555;
}
#admin_footer {
	background:#333333;
	border-top:1px solid #222222;
	clear:both;
	height:30px;
	width:100%;
}
#admin_header .network_title h2 {
	height:45px;
	line-height:45px;
	margin:0;
	padding:0 0 0 20px;
	border:0;
}
#admin_header .network_title h2 a {
	color:white;
}
#admin_header .network_title h2 a:hover {
	color:white;
	text-decoration: underline;
}
#admin_header .network_title h2 a.return_to_network {
	font-size:12px;
	font-weight: normal;
	color:#666666;
	float:right;
	margin-right:40px;
}
#elgg_content.admin_area {
	margin:20px;
	min-height:400px;
	position:relative;
	width:auto;
	background-image: none;
	background-color: transparent;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
#elgg_content.admin_area #elgg_page_contents  {
	float:left;
	margin:0;
	padding:14px;
	width:75%;
	background-color: white;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
}
#elgg_content.admin_area #elgg_sidebar  {
	float:left;
	margin:0;
	min-height:400px;
	padding:0 0 0 3%;
	position:relative;
	width:17%;
}

.admin_area h1,
.admin_area h2,
.admin_area h3,
.admin_area h4,
.admin_area h5,
.admin_area h6 {
	color:#666666;
}
.admin_area #elgg_sidebar .submenu {
	margin:0;
	padding:0;
	list-style: none;
	background-color: transparent;
	background-image: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
}
.admin_area .submenu li.selected a,
.admin_area .submenu li.selected li.selected a,
.admin_area .submenu li.selected li.selected li.selected a {
	background-color: black;
	color:white;
}
.admin_area .submenu li a {
	display:block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
	color:#333333;
}
.admin_area .submenu li a:hover {
	background-color:black;
	color:white;
	text-decoration:none;
}
.admin_area #elgg_sidebar .submenu ul.child {
	margin-bottom:10px;
}
.admin_area .submenu .child li a {
	margin-left:15px;
	background-color:#dedede;
	color:#333333;
}
.admin_area .submenu .child li a:hover {
	background-color:black;
	color:white;
}

.admin_settings h3 {
	background:#999999;
	color:white;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.admin_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.admin_area .input_textarea {
	width:98%;
}
.admin_area form#plugin_settings {
	margin-top: 10px;
}
.admin_area form#plugin_settings .action_button.disabled {
	margin-top:10px;
	float:right;
}


/* ***************************************
	GENERAL FORM ELEMENTS
*************************************** */
/* default elgg core input field classes */
.admin_area .input_text,
.admin_area .input_tags,
.admin_area .input_url,
.admin_area .input_textarea {
	width:98%;
}
.admin_area .input_access {
	margin:5px 0 0 0;
}
.admin_area .input_password {
	width:200px;
}
.admin_area .input_textarea {
	height: 200px;
	width:718px;
}
.admin_area input[type="checkbox"],
.admin_area input.input_radio {
	margin:0 3px 0 0;
	padding:0;
	border:none;
}
.admin_area label {
	font-weight: bold;
	color:#333333;
	font-size: 110%;
}
.admin_area input {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #cccccc;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_area textarea {
	font: 120% Arial, Helvetica, sans-serif;
	border: solid 1px #cccccc;
	padding: 5px;
	color:#666666;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_area textarea:focus,
.admin_area input[type="text"]:focus {
	border: solid 1px #666666;
	background: #f5f5f5;
	color:#333333;
}
.admin_area .input_textarea.monospace {
	font-family:Monaco,"Courier New",Courier,monospace;
	font-size:13px;
}
.admin_area a.longtext_control {
	float:right;
	margin-left:14px;
}
.admin_area .submit_button {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background-color:#0054A7;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:0 10px 10px 0;
	cursor: pointer;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area .submit_button:hover {
	color: white;
	border-color: #333333;
	text-decoration:none;
	background-color:#333333;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.admin_area input[type="password"]:focus {
	border: solid 1px #666666;
	background-color: #f5f5f5;
	color:#333333;
}
.admin_area input[type="submit"] {
	font-size: 14px;
	font-weight: bold;
	color: white;
	text-shadow:1px 1px 0px black;
	text-decoration:none;
	border: 1px solid #0054A7;
	background-color:#0054A7;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 0;
	cursor: pointer;
	-moz-outline-style: none;
	outline: none;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.admin_area input[type="submit"]:hover {
	border-color: #333333;
	text-decoration:none;
	background-color:#333333;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
}
.admin_area .cancel_button {
	font-size: 14px;
	font-weight: bold;
	text-decoration:none;
	color: #333333;
	background-color:#dddddd;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat:  repeat-x;
	background-position:  left 10px;
	border: 1px solid #999999;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin:10px 0 10px 10px;
	cursor: pointer;
}
.admin_area .cancel_button:hover {
	background-color: #999999;
	background-position:  left 10px;
	text-decoration:none;
	color:white;
}
.admin_area .content_header_options .action_button {
	margin-top:0;
	margin-left:10px;
}
.admin_area input.action_button,
.admin_area a.action_button {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	background-color:#cccccc;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	background-position: 0 0;
	border:1px solid #999999;
	color:#333333;
	padding:2px 15px 2px 15px;
	text-align:center;
	font-weight:bold;
	text-decoration:none;
	text-shadow:0 1px 0 white;
	cursor:pointer;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.admin_area input.action_button:hover,
.admin_area a.action_button:hover,
.admin_area input.action_button:focus,
.admin_area a.action_button:focus {
	background-position:0 -15px;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	color:#111111;
	text-decoration: none;
	background-color:#cccccc;
	border:1px solid #999999;
}
.admin_area .action_button:active {
	background-image:none;
}
.admin_area .action_button.disabled {
	color:#999999;
	padding:2px 7px 2px 7px;
}
.admin_area .action_button.disabled:hover {
	background-position:0 -15px;
	color:#111111;
	border:1px solid #999999;
}
.admin_area .action_button.disabled:active {
	background-image:none;
}
.admin_area .action_button.download {
	padding: 5px 9px 5px 6px;
}
.admin_area .action_button.download:hover {

}
.admin_area .action_button.download img {
	margin-right:6px;
	position:relative;
	top:5px;
}
.admin_area .action_button.small {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	width: auto;
	height:8px;
	padding: 4px;
	font-size: 0.9em;
	line-height: 0.6em;
}
.admin_area .action_button.small:hover {
	background-color: #0054A7;
	background-image: none;
	border-color: #0054A7;
	color:white;
	text-shadow:0 -1px 0 #999999;
}



/* ***************************************
	CONTENT HEADER
**************************************** */
#content_header {
	border-bottom:1px solid #CCCCCC;
}
#content_header:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}
.content_header_title {
	float:left;
}
.content_header_title {
	margin-right:10px;
	max-width: 530px;
}
.content_header_title h2 {
	border:none;
	margin-bottom:0;
	padding-bottom:5px;
}
.content_header_options {
	float:right;
}
.content_header_options .action_button {
	float:right;
	margin:0 0 5px 10px;
}



/* REPORTED CONTENT */
.admin_settings.reported_content {
	margin:5px 0 0 0;
	padding:5px 7px 3px 9px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.admin_settings.reported_content p {
	margin:0;
}
.active_report {
	border:1px solid #D3322A;
	background:#F7DAD8;
}
.archived_report {
	border:1px solid #666666;
	background:#dedede;
}
.admin_settings.reported_content .controls {
	float:right;
	margin:14px 5px 0 0;
}
.admin_settings.reported_content a.action_button {
	display:inline;
	float:right;
	margin-left:15px;
}
.admin_settings.reported_content .details_link {
	cursor: pointer;
}



/* ECML */
.ecml_admin_table {
	width:100%;
}
.ecml_admin_table td, th {
	border: 1px solid gray;
	text-align: center;
	padding: 5px;
}
.ecml_admin_table th, .ecml_keyword_desc {
	font-weight: bold;
}
.ecml_row_odd {
	background-color: #EEE;
}
.ecml_row_even {

}
.ecml_restricted {
	color: #555;
}








.admin_settings {
	margin-bottom:20px;
}
.admin_settings table.styled {
	width:100%;
}
.admin_settings table.styled {
	border-top:1px solid #cccccc;
}
.admin_settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.admin_settings table.styled td.column_one {
	width:200px;
}
.admin_settings table.styled tr:hover {
	background: #E4E4E4;
}
.admin_settings.users_online .profile_status {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	line-height:1.2em;
}
.admin_settings.users_online .profile_status span {
	font-size:90%;
	color:#666666;
}
.admin_settings.users_online  p.owner_timestamp {
	padding-left:3px;
}
.admin_plugin_reorder {
	float:right;
	width:200px;
	text-align: right;
}
.admin_plugin_reorder a {
	padding-left:10px;
	font-size:80%;
	color:#999999;
}
.admin_area .manifest_file {
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:5px 10px 5px 10px;
	margin:4px 0 4px 0;
}
.admin_area .admin_plugin_enable_disable {
	width:150px;
	margin:10px 0 0 0;
	float:right;
	text-align: right;
}
.admin_area .admin_plugin_enable_disable a {
	margin:0;
}
.pluginsettings {
	margin:15px 0 5px 0;
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:10px;
}
.pluginsettings h3 {
	padding:0 0 5px 0;
	margin:0 0 5px 0;
	border-bottom:1px solid #999999;
}
#updateclient_settings h3 {
	padding:0;
	margin:0;
	border:none;
}
.plugin_controls {
	padding: 3px 3px 3px 0;
	font-weight: bold;
	float: left;
	width: 150px;
}
form.admin_plugins_simpleview .submit_button {
	margin-right:20px;
}
.plugin_info {
	margin: 3px;
	padding-left: 150px;
	display: block;
}
.plugin_metadata {
	display:block;
	color:#999999;
}
.plugin_name input[type="checkbox"] {
	margin-right: 10px;
}
ul.admin_plugins {
	margin-bottom:0;
	padding-left:0;
	list-style: none;
}
.plugin_details {
	margin:0 0 5px 0;
	padding:0 7px 4px 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.plugin_details p {
	margin:0;
	padding:0;
}
.plugin_details h3 {
	margin-top:-13px;
	color:black;
}
.plugin_settings {
	font-weight: normal;
}
.active {
	border:1px solid #999999;
	background:white;
}
.not_active {
	border:1px solid #999999;
	background:#dedede;
}
.configure_menuitems {
	margin-bottom:30px;
}
.admin_settings.menuitems .input_pulldown {
	margin-right:15px;
	margin-bottom:10px;
}
.admin_settings.menuitems .custom_menuitems {
	list-style: none;
	padding:0;
	margin:0;
}
.admin_settings.menuitems li.custom_menuitem {
	margin-bottom:20px;
}
.admin_notices {
	padding-bottom: 15px;
}
.admin_notices p {
	background-color:#BDE5F8;
	color: black;
	border: 1px solid blue;
	font-weight: bold;
	padding:3px 10px;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}

/* ***************************************
	ELGG TABBED PAGE NAVIGATION
*************************************** */
.elgg_horizontal_tabbed_nav {
	margin-bottom:5px;
	padding: 0;
	border-bottom: 2px solid #cccccc;
	display:table;
	width:100%;
}
.elgg_horizontal_tabbed_nav ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.elgg_horizontal_tabbed_nav li {
	float: left;
	border: 2px solid #cccccc;
	border-bottom-width: 0;
	background: #eeeeee;
	margin: 0 0 0 10px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-webkit-border-top-left-radius:5px;
	-webkit-border-top-right-radius:5px;
}
.elgg_horizontal_tabbed_nav a {
	text-decoration: none;
	display: block;
	padding:3px 10px 0 10px;
	text-align: center;
	height:21px;
	color:#999999;
}
.elgg_horizontal_tabbed_nav a:hover {
	background: #dedede;
	color:#4690D6;
}
.elgg_horizontal_tabbed_nav .selected {
	border-color: #cccccc;
	background: white;
}
.elgg_horizontal_tabbed_nav .selected a {
	position: relative;
	top: 2px;
	background: white;
}
