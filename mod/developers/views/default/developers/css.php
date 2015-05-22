<?php
/**
 * Admin CSS for Elgg Developers plugin
 */
?>
/*<style>*/
/*** Elgg Developer Tools ***/
#developers-iframe {
	width: 100%;
	height: 600px;
	border: none;
}
#developer-settings-form label {
	margin-right: 5px;
}
.elgg-page .jstree-default.jstree-focused {
	background-color: transparent;
}
.developers-log {
	background-color: #EBF5FF;
	border: 1px solid #999;
	color: #666;
	padding: 20px;
}
.developers-gear {
	position: fixed;
	z-index: 1000;
	bottom: 0;
	right: 0;
	cursor: pointer;
	padding: 5px 8px;
}
.developers-gear-popup {
	text-align: right;
}
.developers-gear-popup > section {
	display: inline-block;
	width: 16em;
	padding: 0 20px 20px 0;
	text-align: left;
	vertical-align: top;
}
.developers-gear-popup > section.developers-form {
	width: 20em;
}
.developers-gear-popup h2 {
	margin-bottom: 10px;
}
.developers-gear-popup .elgg-child-menu {
	margin-left: 20px;
	margin-bottom: 10px;
}
.developers-gear-popup .elgg-menu-parent,
.developers-gear-popup .elgg-menu-parent:hover {
	color: #000;
	text-decoration: none;
	cursor: default;
}
.developers-gear-popup .elgg-text-help {
	display: none;
}
.developers-gear-popup label {
	font-weight: inherit;
	font-size: inherit;
}
.developers-gear-popup fieldset > div {
	margin-bottom: 10px;
}
.developers-gear-popup #developer-settings-form  label .elgg-icon-info,
.developers-gear-popup #developer-settings-form  label .elgg-text-help {
	margin-left: 10px;
	vertical-align: text-top;
	cursor: pointer;
}
.developers-gear-popup #developer-settings-form .elgg-foot {
	margin-top: 15px;
	margin-bottom: 0;
}