<?php
/**
 * Core CSS
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/**
 * elgg-body fills the space available to it.
 * It uses hidden text to expand itself. The combination of auto width, overflow
 * hidden, and the hidden text creates this effect.
 *
 * This allows us to float fixed width divs to either side of an .elgg-body div
 * without having to specify the body div's width.
 *
 * @todo check what happens with long <pre> tags or large images
 */
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
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}
/* ***************************************
	Image Block
*************************************** */
.elgg-image-block {
	padding: 3px 0;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 5px;
}

<?php //@todo is this the best way to do it?  Perhaps elgg-image-alt would be cleaner... ?>
.elgg-image-block .elgg-image.elgg-alt {
	float: right;
	margin-left: 5px;
}
/* ***************************************
	List
*************************************** */
.elgg-list {
	border-top: 1px dotted #CCCCCC;
	margin: 5px 0;
	clear: both;
}
.elgg-list > li {
	border-bottom: 1px dotted #CCCCCC;
}
.elgg-list-metadata {
	float: right;
	margin-left: 15px;
	font-size: 90%;
}
.elgg-list-metadata > li {
	float: left;
	margin-left: 15px;
}
.elgg-list-metadata, .elgg-list-metadata a {
	color: #aaaaaa;
}
.elgg-list-item .elgg-subtext {
	margin-bottom: 5px;
}
.elgg-subtext {
	color: #666666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
}
.elgg-tags {
	margin-bottom: 5px;
}
.elgg-list-content {
	margin: 10px 5px;
}
/* ***************************************
	Gallery
*************************************** */
.elgg-gallery {
	border: none;
}
/* ***************************************
	Tables
*************************************** */
<?php //@todo prefix with elgg-.  Move to chrome.php ?>
table.styled {
	width:100%;
}
table.styled {
	border-top:1px solid #cccccc;
}
table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
table.styled td.column-one {
	width:200px;
}
table.styled tr:hover {
	background: #E4E4E4;
}
/* ***************************************
	Modules
*************************************** */

.elgg-aside-module {
	margin-bottom: 20px;
}
.elgg-aside-module .elgg-head {
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 5px;
	padding-bottom: 5px;
}

.elgg-group-module {
	margin-bottom: 20px;
}
.elgg-group-module > .elgg-head {
	padding: 5px 5px 3px;
	background-color: #e4e4e4;
	border-bottom: 1px solid #cccccc;
	-moz-border-radius: 4px 4px 0 0;
}
.elgg-group-module > .elgg-head h3 {
	color: #333333;
}

.elgg-info-module {
	margin-bottom: 20px;
}
.elgg-info-module .elgg-head {
	background: #e4e4e4;
	padding: 5px;
	margin-bottom: 10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}

<?php //@todo should not assume h3 ?>
.elgg-info-module .elgg-head h3 {
	color: #333333;
}

.elgg-popup-module {
	background-color: white;
	border: 1px solid #cccccc;
	z-index: 9999;
	padding: 5px;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
/* ***************************************
	OWNER BLOCK
*************************************** */
.elgg-owner-block {
	margin-bottom: 20px;
}
.elgg-owner-block-menu li {
	float: left;
	width: 50%;
	font-size: 90%;
}
/* ***************************************
	WIDGETS
*************************************** */

.elgg-widgets {
	float: right;
	min-height: 30px;
}
.elgg-widget-add-control {
	text-align: right;
	margin: 5px 5px 15px;
}
.elgg-widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #dedede;
	border: 2px solid #cccccc;
}

<?php //@todo location-dependent style: make an extension of elgg-gallery ?>
.elgg-widgets-add-panel li {
	float: left;
	margin: 2px 10px;
	width: 200px;
	padding: 4px;
	background-color: #cccccc;
	border: 2px solid #b0b0b0;
	font-weight: bold;
}
.elgg-widgets-add-panel li a {
	display: block;
}
.elgg-widgets-add-panel .elgg-state-available {
	color: #333333;
	cursor: pointer;
}
.elgg-widgets-add-panel .elgg-state-available:hover {
	background-color: #bcbcbc;
}
.elgg-widgets-add-panel .elgg-state-unavailable {
	color: #888888;
}

<?php //@todo Still too many location-dependent/overly-qualified styles ?>
.elgg-module-widget {
	background-color: #dedede;
	padding: 2px;
	margin: 0 5px 15px;
	position: relative;
}
.elgg-module-widget:hover {
	background-color: #cccccc;
}
.elgg-module-widget > .elgg-head {
	background-color: #dedede;
	height: 30px;
	line-height: 30px;
	overflow: hidden;
}
.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 0 45px 0 20px;
	color: #333333;
}
.elgg-module-widget.elgg-state-draggable > .elgg-head {
	cursor: move;
}
.elgg-module-widget > .elgg-head a {
	position: absolute;
	top: 5px;
	display: block;
	width: 18px;
	height: 18px;
	border: 1px solid transparent;
}
a.elgg-widget-collapse-button {
	left: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 0px -385px;
}
a.elgg-widget-collapsed {
	background-position: 0px -365px;
}
a.elgg-widget-delete-button {
	right: 5px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -198px 3px;
}
a.elgg-widget-edit-button {
	right: 25px;
	background:transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat -300px -1px;
}
a.elgg-widget-edit-button:hover, a.elgg-widget-delete-button:hover {
	border: 1px solid #cccccc;
}
.elgg-module-widget > .elgg-body {
	background-color: white;
	width: 100%;
	overflow: hidden;
}
.elgg-widget-edit {
	display: none;
	width: 96%;
	padding: 2%;
	border-bottom: 2px solid #dedede;
}
.elgg-widget-content {
	padding: 10px;
}
.elgg-widget-placeholder {
	border: 2px dashed #dedede;
	margin-bottom: 15px;
}

<?php //@todo push into page.php ?>
/* ***************************************
	SYSTEM MESSAGES
*************************************** */

.elgg-system-messages li {
	color:white;
	font-weight:bold;
	display:block;
	padding:3px 10px;
	margin-top:10px;
	cursor:pointer;
	opacity:0.9;
	-webkit-box-shadow:0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow:0 2px 5px rgba(0, 0, 0, 0.45);
}
.elgg-state-success {
	background-color:black;
}
.elgg-state-error {
	background-color:red;
}

.elgg-system-message p {
	margin:0;
}
/* ***************************************
	RIVER
*************************************** */
.elgg-river {
	border-top: 1px solid #CCCCCC;
}
.elgg-river > li {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-river-item {
	padding: 7px 0;
}
.elgg-river-item .elgg-pict {
	margin-right: 20px;
}
.elgg-river-timestamp {
	color: #666666;
	font-size: 85%;
	font-style: italic;
	line-height: 1.2em;
}
.elgg-river-content {
	border-left: 1px solid #CCCCCC;
	font-size: 85%;
	line-height: 1.5em;
	margin: 8px 0 5px 0;
	padding-left: 5px;
}

<?php //@todo location-dependent styles ?>
.elgg-river-content .elgg-user-icon {
	float: left;
}
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

<?php //@todo do we need another elgg-tabs extension? ?>
.elgg-river-comments-tab {
	display: block;
	background-color: #EEEEEE;
	color: #4690D6;
	margin-top: 5px;
	width: auto;
	float: right;
	font-size: 85%;
	padding: 1px 7px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
}

<?php //@todo lists.php ?>
.elgg-river-comments {
	margin: 0;
	border-top: none;
}
.elgg-river-comments li:first-child {
	-moz-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
}
.elgg-river-comments li:last-child {
	-moz-border-radius-bottomleft: 5px;
	-moz-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.elgg-river-comments li {
	background-color: #EEEEEE;
	border-bottom: none;
	padding: 4px;
	margin-bottom: 2px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
	background-color: #EEEEEE;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	padding: 2px 4px;
	font-size: 85%;
	margin-bottom: 2px;
}

<?php //@todo location-dependent styles ?>
.elgg-river-item form {
	background-color: #EEEEEE;
	padding: 4px 4px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	display: none;
	height: 30px;
}
.elgg-river-item input[type=text] {
	width: 80%;
}
.elgg-river-item input[type=submit] {
	margin: 0 0 0 10px;
}
.elgg-river-item > .elgg-alt a {
	font-size: 90%;
	float: right;
	clear: both;
}

/* ***************************************
	LIKES
*************************************** */
.elgg-likes-list {
	width: 345px;
	position: absolute;
}

/* ***************************************
	TAGS
*************************************** */
.elgg-tags {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: left -196px;
	padding:1px 0 0 14px;
	font-size: 85%;
}
.elgg-tags li {
	display: inline;
	margin-right: 5px;
}
.elgg-tags li:after {
	content: ",";
}
.elgg-tags li:last-child:after {
	content: "";
}
.elgg-tagcloud {
	text-align: justify;
}