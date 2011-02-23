<?php
/**
 * CSS form elements
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	Form Elements
*************************************** */
<?php //@todo not comfortable with these... ?>
fieldset > div {
	margin-bottom: 15px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}

label {
	font-weight: bold;
	color: #333333;
	font-size: 110%;
}

input, textarea {
	font: 120% Arial, Helvetica, sans-serif;
	color: #666666;
	
	padding: 5px;
	
	border: 1px solid #cccccc;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	width: 100%;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
}

input:focus, textarea:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333333;
}

textarea {
	height: 200px;
}


.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}


.elgg-input-access {
	margin:5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin:0 3px 0 0;
	padding:0;
	border:none;
	width:auto;
}
.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radio.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
}

input[type="submit"],
input[type="button"],
.elgg-button {
	font-size: 14px;
	font-weight: bold;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: auto;
	padding: 2px 4px;
	margin: 10px 0 10px 0;
	cursor: pointer;
	outline: none;
	-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
}
input[type="submit"],
.elgg-button-submit {
	color: white;
	text-shadow: 1px 1px 0px black;
	text-decoration: none;
	border: 1px solid #4690d6;
	background-color: #4690d6;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
}
input[type="submit"]:hover,
.elgg-button-submit:hover {
	border-color: #0054a7;
	text-decoration: none;
	color: white;
	background-color: #0054a7;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
}
.elgg-button-cancel {
	color: #333333;
	background-color: #dddddd;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
	background-repeat: repeat-x;
	background-position: left 10px;
	border: 1px solid #999999;
}
.elgg-button-cancel:hover {
	color: white;
	background-color: #999999;
	background-position: left 10px;
	text-decoration: none;
}
.elgg-button-action {
	background-color:#cccccc;
	background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat:  repeat-x;
	background-position: 0 0;
	border:1px solid #999999;
	color: #333333;
	padding: 2px 15px 2px 15px;
	text-align: center;
	font-weight: bold;
	text-decoration: none;
	text-shadow: 0 1px 0 white;
	cursor: pointer;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
.elgg-button-action:hover,
.elgg-button-action:focus {
	background-position: 0 -15px;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
	background-repeat: repeat-x;
	color: #111111;
	text-decoration: none;
	background-color: #cccccc;
	border: 1px solid #999999;
}

/* small round delete button */
.elgg-button-delete {
	width:14px;
	height:14px;
	margin:0;
	float:right;
}
.elgg-button-delete a {
	display:block;
	cursor: pointer;
	width:14px;
	height:14px;
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png") no-repeat -200px top;
	text-indent: -9000px;
	text-align: left;
}
.elgg-button-delete a:hover {
	background-position: -200px -16px;
}
.elgg-button-dropdown {
	padding:3px 6px;
	text-decoration:none;
	display:block;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color: white;
	border:1px solid #71B9F7;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
	/*background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;*/
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size:smaller;
}

.elgg-button-dropdown:hover {
	background-color:#71B9F7;
	text-decoration:none;
}

.elgg-button-dropdown.elgg-state-active {
	background: #cccccc;
	outline: none;
	color: #333333;
	
	border:1px solid #cccccc;
	-webkit-border-radius:4px 4px 0 0;
	-moz-border-radius:4px 4px 0 0;
	border-radius:4px 4px 0 0;
}