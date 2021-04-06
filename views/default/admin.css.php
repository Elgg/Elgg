<?php
/**
 * Elgg Admin CSS
 *
 * This is a distinct theme from the theme of the site. There are dependencies
 * on the HTML created by the views in Elgg core.
 */

echo elgg_view('core.css');

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

	.elgg-admin-notices-dismiss-all {
		font-weight: 600;
		margin: 1rem;
		display: block;
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
		margin: 1rem 0.5rem;
	}

	.elgg-page-topbar .elgg-menu-container {
		margin-right: 0;
		margin-left: auto;
	}
}

@media only $(media-desktop-down) {
	.elgg-page-admin {
		.elgg-page-topbar .elgg-menu-container {
			margin-left: 0;
		}
	}
}

/* ***************************************
	Admin informationals
**************************************** */
.elgg-admin-information-row {
	> .elgg-image {
		min-width: 1rem;
		text-align: center;
		
		.elgg-icon-times {
			color: $(state-danger-font-color);
		}
		.elgg-icon-exclamation-triangle {
			color: $(state-warning-font-color);
		}
		.elgg-icon-checkmark {
			color: $(state-success-font-color);
		}
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
	border: 1px solid $(border-color-mild);
	padding: 0.5rem;
	border-radius: 3px;
	position: relative;
	
	&:hover {
		border-color: $(border-color-highlight);
	}

	&.elgg-state-active {
		background: #fff;
	}
	
	&.elgg-state-inactive {
		background: #eee;
		
		.elgg-listing-summary-title a {
			color: #666;
		}
	}
	
	&.elgg-state-cannot-activate {
		background: #f7f0d4;
	}
	
	&.elgg-state-draggable > .elgg-image-block .elgg-head {
		cursor: move;
	}
	
	> .elgg-image {
		margin-right: .5rem;
		min-width: 9rem;
		text-align: center;
		
		.elgg-button {
			display: block;
			margin: 0;
		}
	}
	
	.ui-sortable-handle {
		cursor: move;
	}
	
	.elgg-state-error {
		background: $(state-danger-background-color);
		color: $(state-danger-font-color);
		border-color: $(state-danger-border-color);
	}
	
	.elgg-state-warning {
		background: $(state-warning-background-color);
		color: $(state-warning-font-color);
		border-color: $(state-warning-border-color);
	}
	
	.elgg-state-error,
	.elgg-state-warning {
		padding: 3px 6px;
		margin: 3px 0;
		width: auto;

		a {
			text-decoration: underline;
		}
	}
}

.elgg-state-cannot-activate .elgg-image a[disabled],
.elgg-state-cannot-deactivate .elgg-image a[disabled] {
	text-decoration: none;
}

.elgg-plugin-placeholder {
	display: block;
	min-height: 5rem;
	border: 1px dashed $(border-color-highlight) !important;
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

#elgg-plugin-list {
	position: relative;
	
	.elgg-plugin {
		.elgg-state-error, .elgg-state-warning {
			display: inline-block;
		}
	}
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
