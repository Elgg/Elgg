<?php
/**
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* **************************
	BUTTONS
************************** */
.elgg-button {
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;	
	color: #FFF;
	width: auto;
	padding: 5px 12px;
	cursor: pointer;
    background: #4787B8;	
	border: 1px solid #3873AB; /* Fallback */
	border: 1px solid rgba(0, 0, 0, 0.2);
	
	border-radius:	3px;
	box-shadow: inset 0 0 1px rgba(255, 255, 255, 0.6);
}
a.elgg-button {
	padding: 6px 12px;
}
.elgg-button:hover {
	background: #60B6F7;
	text-decoration: none;
	color: #FFF;
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
	background: #E38F07;
}
.elgg-button-delete {
	border: 1px solid #CC2900; /* Fallback */
	border: 1px solid rgba(0, 0, 0, 0.2);
	background: #FF3300;
}
.elgg-button-delete:hover {
	background: #D63006;
}
.elgg-button-dropdown {
	background: none;
	text-decoration: none;
	display: block;
	position: relative;
	margin-left: 0;	
	color: #FFF;
	border: none;
	box-shadow: none;
	border-radius:	0;
}
.elgg-button-dropdown:hover {
	color: #FFF;
	background: #60B8F7;
	text-decoration: none;
}
.elgg-button-dropdown.elgg-state-active {
	background: #60B8F7;
	outline: none;
	color: #FFF;
}
