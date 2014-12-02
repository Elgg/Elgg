<?php
/**
 * Maintenance mode CSS
 */

$url = elgg_get_site_url();

echo elgg_view('css/elements/reset', $vars);
echo elgg_view('css/elements/core', $vars);
echo elgg_view('css/elements/helpers', $vars);

?>
/* <style> /**/

body {
	font-size: 80%;
	font-family: "Lucida Grande", Arial, Tahoma, Verdana, sans-serif;
	line-height: 1.4em;
}
h1, h2, h3, h4, h5, h6 {
	color: #666;
    font-weight: bold;
}
h1 {
	font-size: 2em;
	margin-bottom: 1em;
}
h3 {
	font-size: 1.3em;
	margin-bottom: 0.4em;
}
a {
	color: #999;
}
p {
	margin-bottom: 15px;
}
p:last-child {
	margin-bottom: 0;
}


/* ***************************************
	LAYOUT
*************************************** */
.elgg-body-maintenance {
	margin: 100px auto 0 auto;
	position: relative;
	width: 530px;
}
.elgg-module-maintenance {
	position: absolute;
	top: 0;
	left: 0;
}
.elgg-module-maintenance > .elgg-head {
	height: 17px;
}
.elgg-module-maintenance > .elgg-body {
	padding: 10px 20px;
}
.elgg-module-maintenance > .elgg-foot {
	height: 17px;
}
.elgg-module-maintenance > .elgg-head {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_top.png) no-repeat left top;
}
.elgg-module-maintenance > .elgg-body {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_middle.png) repeat-y left top;
}
.elgg-module-maintenance > .elgg-foot {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_bottom.png) no-repeat left top;
}
.elgg-system-messages {
	position: fixed;
	top: 24px;
	right: 20px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}
.elgg-output {
	margin-bottom: 3em;
}
.elgg-module-maintenance-login {
	font-size: 12px;
	line-height: 1.4em;
	width: 200px;
	float: right;
	margin: 0;
	border: 1px solid #ccc;
	padding: 5px;
	border-radius: 5px;
}

/* ***************************************
	USER INPUT DISPLAY RESET
*************************************** */

.elgg-output {
	margin-top: 10px;
}
.elgg-output dt { font-weight: bold }
.elgg-output dd { margin: 0 0 1em 1em }

.elgg-output ul, .elgg-output ol {
	margin: 0 1.5em 1.5em 0;
	padding-left: 1.5em;
}
.elgg-output ul {
	list-style-type: disc;
}
.elgg-output ol {
	list-style-type: decimal;
	list-style-position: inside;
	padding-left: .4em;
}
.elgg-output table {
	border: 1px solid #ccc;
}
.elgg-output table td {
	border: 1px solid #ccc;
	padding: 3px 5px;
}
.elgg-output img {
	max-width: 100%;
	height: auto;
}

/* ***************************************
	Form Elements
*************************************** */
fieldset > div {
	margin-bottom: 5px;
}
fieldset > div:last-child {
	margin-bottom: 0;
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
	border-radius: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus,
input[type=url]:focus,
textarea:focus {
	border: solid 1px #aaa;
	background: #eee;
	color:#333;
	/* We remove outlines from specific input types so we can leave the browser
	   defaults (like glows) for everything else */
	outline: 0 none;
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
.elgg-button {
	font-size: 14px;
	font-weight: bold;
	border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	cursor: pointer;
	box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
.elgg-button-submit {
	background-color: #666;
	border-color: #555;
	color: white;
	text-shadow: 1px 1px 0px black;
	text-decoration: none;
}
.elgg-button-submit:hover {
	background-color: #333;
	border-color: #222;
}

/* ***************************************
	Messages
*************************************** */
.elgg-message {
	color: white;
	display: block;
	padding: 3px 10px;
	opacity: 0.9;
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	border-radius: 8px;
	font-weight: bold;
}
.elgg-state-success {
	background-color: black;
}
.elgg-state-error {
	background-color: red;
}
.elgg-state-notice {
	background-color: #4690D6;
}
