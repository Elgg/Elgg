<?php
/**
 * Maintenance mode CSS
 */

echo elgg_view('elements/reset.css', $vars);
echo elgg_view('elements/helpers.css', $vars);
echo elgg_view('elements/z-index.css', $vars);
echo elgg_view('elements/components/messages.css', $vars);

?>
/* <style> /**/

body {
	font-size: 90%;
	line-height: 1.4em;
	font-family: "Helvetica Neue", Helvetica, "Lucida Grande", Arial, sans-serif;
}
h1, h2, h3, h4, h5, h6 {
	color: #2d3047;
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

.elgg-page-maintenance {
	margin: 0;
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	
	.elgg-body-maintenance {
		min-width: 30rem;
		max-width: 50%;
		min-height: 100%;
		
		background: #fff;
		border: 1px solid #DEDEDE;
		padding: 2rem;
		
		border-radius: 4px;
		box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
	}
}

.elgg-system-messages {
	position: fixed;
	top: 32px;
	right: 20px;
	max-width: 500px;
}

.elgg-module {
	> .elgg-head {
		padding: 1rem 0;
	}
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
	
	dt {
		font-weight: bold;
	}
	
	dd {
		margin: 0 0 1em 1em;
	}
	
	ul {
		list-style-type: disc;
		margin: 0 1.5em 1.5em 0;
		padding-left: 1.5em;
	}
	
	ol {
		list-style-type: decimal;
		list-style-position: inside;
		margin: 0 1.5em 1.5em 0;
		padding-left: .4em;
	}
	
	table {
		border: 1px solid #DCDCDC;
		
		td {
			border: 1px solid #DCDCDC;
			padding: 3px 5px;
		}
	}
	
	img {
		max-width: 100%;
		height: auto;
	}
}

/* ***************************************
	Form Elements
*************************************** */

.elgg-form-body,
.elgg-field {
	margin-bottom: 15px;
	
	&:last-child {
		margin-bottom: 0;
	}
}

label {
	font-weight: bold;
	color: #333;
	font-size: 90%;
}
input, textarea {
	border: 1px solid #DCDCDC;
	color: #666;
	font: 100% Arial, Helvetica, sans-serif;
	padding: 7px 6px;
	width: 100%;
	border-radius: 3px;
	box-sizing: border-box;
}

input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus,
input[type=number]:focus,
input[type=url]:focus,
textarea:focus {
	border: solid 1px #C2C2C2;
	background: #e6e6ea;
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
input[type="number"] {
	-moz-appearance: textfield;
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
