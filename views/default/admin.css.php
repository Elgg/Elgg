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

echo elgg_view('elements/reset.css', $vars);
echo elgg_view('elements/core.css', $vars);
echo elgg_view('elements/grid.css', $vars);
echo elgg_view('elements/typography.css', $vars);
echo elgg_view('elements/forms.css', $vars);
echo elgg_view('elements/buttons.css', $vars);
echo elgg_view('elements/icons.css', $vars);
echo elgg_view('elements/navigation.css', $vars);
echo elgg_view('elements/modules.css', $vars);
echo elgg_view('elements/widgets.css', $vars);
echo elgg_view('elements/components.css', $vars);
echo elgg_view('elements/layout.css', $vars);
echo elgg_view('elements/misc.css', $vars);
echo elgg_view('elements/misc/spinner.css', $vars);
echo elgg_view('elements/fa.css', $vars);
echo elgg_view('elements/helpers.css', $vars);

?>

.elgg-page-admin {
	background: #f7f7f8;

	.elgg-page-section > .elgg-inner {
		max-width: 100rem;
		margin: 0 auto;
	}

	.elgg-system-messages {
		position: relative;
		margin-top: 2rem;
		width: 100%;
		max-width: 100%;
		top: 0;
		right: 0;
	}

	.elgg-admin-notices > li {
		padding: 0;
		border: none;
	}

	.elgg-page-topbar {
		background: #2d3047;
	}

	.elgg-main {
		padding: 2rem;
		background: #ffffff;
		border: 1px solid #e6e6ea;
		margin: 1rem auto;
	}

	.elgg-page-topbar .elgg-menu-container {
		margin-right: 0;
		margin-left: auto;
	}

	.elgg-menu-page {
		background: #ffffff;
		border: 1px solid #e6e6ea;
		margin-bottom: 2rem;
	}

	.elgg-menu-page-container .elgg-menu-section-header {
		margin: 0;
		border: none;
	}

	.elgg-menu-page li:not(:last-child) {
		border-bottom: 1px solid #f7f7f8;
	}

	.elgg-menu-page .elgg-child-menu {
		border-top: #f7f7f8;
	}

	.elgg-menu-page .elgg-menu-item-has-toggle > .elgg-child-menu {
		margin: 0;
		border-top: 1px solid #f7f7f8;
	}

	.elgg-menu-page .elgg-menu-item-has-toggle > .elgg-child-menu > li > a {
		padding-left: 2rem;
	}
}
/* ***************************************
	PLUGINS FILTER
**************************************** */
.elgg-admin-plugins-categories {
	display: flex;
	flex-wrap: wrap;
	flex-direction: row;
	margin-top: 1rem;
}

.elgg-admin-plugins-categories > li {
	margin: 0.1rem;
	display: inline-block;
}

.elgg-admin-plugins-categories > li > a {
	padding: 0.25rem 0.5rem;
	background: #e6e6ea;
	border-radius: 3px;
	font-size:0.85rem;
	color: #2d3047;
	text-decoration: none;
}

.elgg-admin-plugins-categories > li.elgg-state-selected > a {
	color: #fff;
	background: #2d3047;
	text-decoration: none;
}

/* ***************************************
	PLUGINS
**************************************** */

#elgg-plugin-list .elgg-list > li {
	padding: 0;
	border: none;
	margin-bottom: 2px;
}

.elgg-plugin {
	border: 1px solid #CCC;
	padding: 0.5rem;
	border-radius: 3px;
}

.elgg-plugin > .elgg-image-block {
	align-items: center;
}

.elgg-plugin .elgg-output {
	margin: 0;
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

.elgg-plugin > .elgg-image-block > .elgg-image .elgg-button {
	display: block;
	margin: 0;
	padding: 3px 10px;
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
	margin-left: 0.5rem;
	font-size: 0.85rem;
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
	float: left;
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
	margin-left: 5px;
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

#elgg-plugin-list {
	position: relative;
	
	.elgg-plugin {
		.elgg-state-error, .elgg-state-warning {
			display: inline-block;
		}
	}
}

.elgg-plugin .elgg-state-error a,
.elgg-plugin .elgg-state-warning a,
.elgg-plugin .elgg-text-help a {
	text-decoration: underline;
}

.elgg-plugin-title {
	font-weight: 500;
}

.elgg-state-inactive .elgg-plugin-title {
	color: #666;
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

/* ***************************************
	MISC
*************************************** */
.elgg-content-thin {
	max-width: 600px;
}

table.mceLayout {
	width: 100% !important;
}