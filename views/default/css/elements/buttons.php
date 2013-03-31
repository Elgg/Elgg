<?php
/**
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* **************************
	BUTTONS
************************** */
.elgg-button {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	color: #FFFFFF;
	width: auto;
	padding: 5px 12px;
	cursor: pointer;
    border: none;
    background: #4690D6;	
	border: 1px solid #3873AB; /* Fallback */
	border: 1px solid rgba(0, 0, 0, 0.2);

	border-radius:	3px;		
	box-shadow: inset 0 0 1px rgba(255, 255, 255, 0.6);
}
a.elgg-button {
	padding: 6px 12px;
}
.elgg-button:hover,
.elgg-button-action:focus {
	background: #71B9F7;
	text-decoration: none;
	color: #FFFFFF;
}
.elgg-button-submit.elgg-state-disabled {
	background: #DEDEDE;
	cursor: default;
}
.elgg-button-cancel {
	border: 1px solid #C88415; /* Fallback */
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #FAA51A;
}
.elgg-button-cancel:hover {
	background-color: #E38F07;
}
.elgg-button-delete {
	border: 1px solid #CC2900; /* Fallback */
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #FF3300;
}
.elgg-button-delete:hover {
	background-color: #D63006;
}

.elgg-button-dropdown {
	padding: 3px 6px;
	text-decoration: none;
	display: block;
	font-weight: bold;
	position: relative;
	margin-left: 0;
	color: white;
	border: 1px solid #71B9F7;
	border-radius: 3px;
	box-shadow: 0 0 0;
}
.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size: smaller;
}
.elgg-button-dropdown:hover {
	background-color: #71B9F7;
	text-decoration: none;
}
.elgg-button-dropdown.elgg-state-active {
	background: #ccc;
	outline: none;
	color: #333;
	border: 1px solid #ccc;
	border-radius: 3px 3px 0 0;
}
