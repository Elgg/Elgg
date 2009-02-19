<?php

	/**
	 * Elgg thewire CSS extender
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

?>

.thewire-post {
	background:#efefef;
	margin-bottom:10px;
}

.thewire-post .note_date {
	font-size:90%;
	color:#666666;
	background:#ffffff url(<?php echo $vars['url']; ?>mod/thewire/graphics/thewire_speech_bubble.gif) no-repeat right top; 
	height:20px;
	padding:0;
}

.thewire_icon {
    float:left;
    margin:4px 10px 4px 4px;
}
.note_body {
	margin:0;
	padding:6px 4px 4px 4px;
	min-height: 40px;
	line-height: 1.4em;
}

.thewire-post .reply {
	font: 11px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 2px solid #4690d6;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	width: auto;
	padding: 0 3px 2px 3px;
	margin:0 0 5px 5px;
	cursor: pointer;
	float:right;
}
.thewire-post .reply:hover {
	background: #0054a7;
	border: 2px solid #0054a7;
	text-decoration: none;
}
.thewire_options {
	float:right;
}
.thewire-post .delete_note a {
	display:block;
	float:right;
	cursor: pointer;
	width:14px;
	height:14px;
	margin:3px 3px 0 0;
	background: url("<?php echo $vars['url']; ?>_graphics/icon_customise_remove.png") no-repeat 0 0;
	text-indent: -9000px;
}
.thewire-post .delete_note a:hover {
	background-position: 0 -16px;
}
/*-------------------------------
REPLY DIV AND FORM
-------------------------------*/


/* used on shout out form, reply form */
textarea#thewire_large-textarea {
	width: 668px;
	height: 80px;
	padding: 6px;
	font-family: Arial, 'Trebuchet MS','Lucida Grande', sans-serif;
	font-size: 100%;
	color:#666666;
	margin-bottom:10px;
}

input.thewire_characters_remaining_field { 
	color:#333333;
	border:none;
	font-size: 100%;
	font-weight: bold;
	padding:0 2px 0 0;
	margin:0;
	text-align: right;
}
.thewire_characters_remaining {
	text-align: right;
}

input#thewire_submit_button {
	background-color: #3399cc;/* blue */
	color:#ffffff;
	font-size: 11px;
	font-weight: bold;
	text-decoration:none;
	margin:0;
	padding:4px;
	border:none;
	cursor:pointer;
}

input#thewire_submit_button:hover {
	background-color: #000000;
}