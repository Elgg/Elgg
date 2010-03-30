<?php

	/**
	 * Elgg thewire CSS extender
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

?>
/* widget */
.thewire-singlepage {
	margin:0 10px 0 10px;
}
.thewire-singlepage .note_body {
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}
.collapsable_box_content .note_body {
	line-height:1.2em;
}
.thewire-singlepage .thewire-post {
	margin-bottom:5px;
	background:transparent url(<?php echo $vars['url']; ?>mod/thewire/graphics/thewire_speech_bubble.gif) no-repeat right bottom; 
}
.thewire-post {
	background:#cccccc;
	margin-bottom:10px;
}
.thewire-post .note_date {
	font-size:90%;
	color:#666666;
	padding:0;
}
.thewire_icon {
    float:left;
    margin:0 8px 4px 2px;
}
.note_body {
	margin:0;
	padding:6px 4px 4px 4px;
	min-height: 40px;
	line-height: 1.4em;
	overflow: hidden;
}
.thewire_options {
	float:right;
	width:65px;
}
.thewire-post .reply {
	font: 11px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#999999;
	border: 2px solid #999999;
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	width: auto;
	padding: 0 3px 2px 3px;
	margin:0 0 5px 5px;
	cursor: pointer;
	float:right;
}
.thewire-post .reply:hover {
	background: #4690d6;
	border: 2px solid #4690d6;
	color:white;
	text-decoration: none;
}
.thewire-post .delete_note {
	width:14px;
	height:14px;
	margin:3px 0 0 0;
	float:right;
}
.thewire-post .delete_note a {
	display:block;
	cursor: pointer;
	width:14px;
	height:14px;
	background: url("<?php echo $vars['url']; ?>_graphics/icon_customise_remove.png") no-repeat 0 0;
	text-indent: -9000px;
}
.thewire-post .delete_note a:hover {
	background-position: 0 -16px;
}
/* IE 6 fix */
* html .thewire-post .delete_note a { background-position-y: 2px; }
* html .thewire-post .delete_note a:hover { background-position-y: -14px; }

.post_to_wire {
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	margin:0 10px 10px 10px;
	padding:10px;	
}
.post_to_wire input[type="submit"] {
	margin:0;
}

/* reply form */
textarea#thewire_large-textarea {
	width: 664px;
	height: 40px;
	padding: 6px;
	font-family: Arial, 'Trebuchet MS','Lucida Grande', sans-serif;
	font-size: 100%;
	color:#666666;
}
/* IE 6 fix */
* html textarea#thewire_large-textarea { 
	width: 642px;
}

input.thewire_characters_remaining_field { 
	color:#333333;
	border:none;
	font-size: 100%;
	font-weight: bold;
	padding:0 2px 0 0;
	margin:0;
	text-align: right;
	background: white;
}
input.thewire_characters_remaining_field:focus {
	border:none;
	background:white;
}
.thewire_characters_remaining {
	text-align: right;
}

