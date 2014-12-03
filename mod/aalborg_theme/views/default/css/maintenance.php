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
	font-size: 90%;
	line-height: 1.4em;
	font-family: "Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
}
h1, h2, h3, h4, h5, h6 {
	color: #444;
    font-weight: bold;
}
h1 {
	font-size: 1.8em;
	margin-bottom: 1em;
}
h3 {
	font-size: 1.5em;
	line-height: 1.1em;
	margin-bottom: 5px;
}
a {
	color: #999;
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
	
    background-color: #FFF;
    border: 1px solid #DEDEDE;
    padding: 10px;

	border-radius: 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}
.elgg-module-maintenance > .elgg-head {
    padding: 20px 20px 0 20px;
}
.elgg-module-maintenance > .elgg-body {
    padding: 0 20px;
}
.elgg-module-maintenance > .elgg-foot {
    padding: 0 20px 20px 20px;
}

.elgg-system-messages {
	position: fixed;
	top: 32px;
	right: 20px;
	max-width: 500px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

.elgg-output {
	font-size: 14px;	
	margin-top: 20px;
	padding: 20px;
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
	border-radius: 5px;	
	margin-bottom: 42px;
}
.elgg-form-login {
	max-width: 470px;
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
	border: 1px solid #DCDCDC;
}
.elgg-output table td {
	border: 1px solid #DCDCDC;
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
	margin-bottom: 15px;
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

/* **************************
	BUTTONS
************************** */
.elgg-button {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	color: #FFF;
	width: auto;
	padding: 5px 12px;
	cursor: pointer;

	border-radius: 3px;
	box-shadow: inset 0 0 1px rgba(255, 255, 255, 0.6);
}
.elgg-button-submit {
	background-color: #666;
	border-color: #555;
	color: white;
	text-shadow: none;
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
	color: #FFF;
	display: block;
	padding: 10px 20px;
	cursor: pointer;
	opacity: 0.9;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
	border-radius: 3px;
}
.elgg-state-success {
	background-color: #090;
}
.elgg-state-error {
	background-color: #F00;
}
.elgg-state-notice {
	background-color: #5097CF;
}

/* ***************************************
	RESPONSIVE
*************************************** */

@media (max-width: 600px) {
	.elgg-page-maintenance {
		padding: 20px;
	}
    .elgg-body-maintenance {
    	margin: 40px auto 0;
        width: auto;
    }
}