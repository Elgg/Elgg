<?php
/**
 * Elgg Admin CSS
 *
 * This is a distinct theme from the theme of the site. There are dependencies
 * on the HTML created by the views in Elgg core.
 *
 * @package Elgg.Core
 * @subpackage UI
 */

echo elgg_view('elgg.css');
?>

.elgg-admin-notices p {
	color: #3B8BC9;
	background-color: #E7F1F9;
	border: 1px solid #B1D1E9;
	padding: 20px;
	border-radius: 3px;
}
.elgg-admin-notices a.elgg-admin-notice {
	float: right;
	text-decoration: none;
}

.elgg-admin-notices a {
	text-decoration: underline;
}

/* ***************************************
	PLUGINS
**************************************** */
#elgg-plugin-list .elgg-list > li {
    padding: 0;
}
.elgg-admin-plugins-categories li {
	padding-right: 5px;
}
.elgg-plugin {
	padding: 0 5px;
	border-radius: 5px;
}
.elgg-plugin:hover {
	border-color: #999;
}
.elgg-plugin .elgg-head {
	white-space: nowrap;
	overflow: hidden;
	max-width: 100%;
}
.elgg-plugin.elgg-state-draggable > .elgg-image-block .elgg-head {
	cursor: move;
}
.elgg-plugin > .elgg-image-block > .elgg-image {
	margin-right: 0;
	min-width: 9em;
	text-align: center;
}
.elgg-plugin > .elgg-image-block > .elgg-body {
	padding: 3px 10px;
}

.elgg-plugin p {
	margin: 0;
}
.elgg-plugin h3 {
	color: black;
	padding-bottom: 10px;
}

.elgg-plugin-list-description {
	display: inline-block;
	color: #999;
}

.elgg-plugin.elgg-state-active,
.elgg-state-active .elgg-plugin-list-reordering {
	background: #fff;
}
.elgg-plugin.elgg-state-inactive,
.elgg-state-inactive .elgg-plugin-list-reordering {
	background: #eee;
}

.elgg-plugin.elgg-state-cannot-activate,
.elgg-plugin.elgg-state-cannot-activate .elgg-plugin-list-reordering {
	background: #f7f0d4;
}
.elgg-state-cannot-activate .elgg-image a[disabled],
.elgg-state-cannot-deactivate .elgg-image a[disabled] {
	text-decoration: none;
}

.elgg-plugin-list-reordering {
	float: right;
	display: none;
	position: relative;
}
.elgg-plugin:hover .elgg-plugin-list-reordering {
	display: block;
}

.elgg-plugin-list-reordering li {
	float:left;
	margin-left: 5px;
}

#elgg-plugin-list-cover {
	display: none;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: white;
	opacity: 0.5;
}

.elgg-plugin-settings {
	font-weight: normal;
	font-size: 0.9em;
	margin: 0 5px;
}
.elgg-plugin-contributors {
	list-style-position: inside;
	list-style-type: circle;
}
.elgg-plugin-contributors li {
	font-style: italic;
}
.elgg-plugin-contributors dl,
.elgg-plugin-contributors dd {
	display: inline;
	padding-right: 5px
}
.elgg-plugin-contributors dt {
	display: none;
}
.elgg-plugin-contributors dd:after {
	content: ', ';
}
.elgg-plugin-contributors dd.elgg-plugin-contributor-name:after {
	content: ' - ';
}
.elgg-plugin-contributors dd.elgg-plugin-contributor-description:after {
	content: '';
}
.elgg-plugin .elgg-state-error {
	background: #fbe3e4;
	color: #8a1f11;
	border-color: #fbc2c4;
	padding: 3px 6px;
	margin: 3px 0;
	width: auto;
}
.elgg-plugin .elgg-state-warning {
	background: #f4f4f4;
	color: #000000;
	border-color: #fbe58b;
	padding: 3px 6px;
	margin: 3px 0;
	width: auto;
}
.elgg-plugin-list-error .elgg-state-error,
.elgg-plugin-list-error .elgg-state-warning {
	display: inline-block;
}
.elgg-plugin .elgg-state-error a,
.elgg-plugin .elgg-state-warning a,
.elgg-plugin .elgg-text-help a {
	text-decoration: underline;
}

.elgg-plugin-title {
	font-weight: bold;
}

.elgg-state-inactive .elgg-plugin-title {
	color: #666;
}

.elgg-plugin-more {
	background-color: #eee;
	border-radius: 8px;
	padding: 5px 10px;
	margin: 4px 0;
}
ul.elgg-plugin-categories, ul.elgg-plugin-categories > li,
ul.elgg-plugin-resources, ul.elgg-plugin-resources > li {
	display: inline;
}

.elgg-plugin .elgg-menu-hz > li:after {
	content: ",";
	padding-right: 10px;
}

.elgg-module-plugin-details .elgg-plugin {
	border: none;
	margin: 0;
	padding: 0;
}

.elgg-module-plugin-details {
	width: 600px;
	min-height: 500px;
}

.elgg-module-plugin-details .elgg-tabs a {
	cursor: pointer;
}

.elgg-plugin-details-screenshots > ul {
	text-align: center;
}

.elgg-plugin-details-screenshots > div {
	text-align: center;
}

.elgg-plugin-details-screenshots > div > img {
	max-height: 380px;
	max-width: 480px;
}

.elgg-plugin-details-screenshots > div > img.elgg-state-selected {
	display: inline-block;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot {
	display: inline;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot img {
	height: 50px;
	border: 1px solid #ccc;
}
.elgg-plugin-details-screenshots > ul .elgg-plugin-screenshot.elgg-state-selected img {
	border: 1px solid #999;
}

/****************************************
	MARKDOWN
****************************************/
.elgg-markdown {
	margin: 15px;
}
.elgg-markdown h1,
.elgg-markdown h2,
.elgg-markdown h3,
.elgg-markdown h4,
.elgg-markdown h5,
.elgg-markdown h6 {
	margin: 1em 0 1em -15px;
	color: #333;
}
.elgg-markdown ol {
	list-style: decimal;
	padding-left: 2em;
}
.elgg-markdown ul {
	list-style: disc;
	padding-left: 2em;
}
.elgg-markdown p {
	margin: 15px 0;
}
.elgg-markdown img {
	max-width: 100%;
	height: auto;
	margin: 10px 0;
}
.elgg-markdown pre > code {
	border: none;
}
