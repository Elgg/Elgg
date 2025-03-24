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

	.elgg-admin-notices-dismiss-all {
		font-weight: 600;
		margin: 1rem;
		display: block;
	}

	.elgg-module-admin-notices {
		margin-top: 2rem;
		
		> li {
			padding: 0;
			border: none;
		}
	}

	.elgg-page-topbar {
		background: #2d3047;
		padding: 0 1rem;
		
		.elgg-menu-container {
			justify-content: space-between;
			width: 100%;
			
			.elgg-menu li .elgg-child-menu {
				background: #2d3047;
			}
		}
	}
	
	.elgg-page-body {
		padding: 2rem 1rem;
	}
	
	.elgg-page-footer {
		padding: 1rem;
	}
}

@media only $(media-desktop-up) {
	.elgg-page-admin {
		.elgg-page-topbar .elgg-menu-admin-header-alt {
			order: 2;
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
			color: var(--elgg-state-danger-font-color);
		}
		.elgg-icon-exclamation-triangle {
			color: var(--elgg-state-warning-font-color);
		}
		.elgg-icon-checkmark {
			color: var(--elgg-state-success-font-color);
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
	grid-gap: 0.5rem;
	
	> a {
		padding: 0.25rem 0.5rem;
		background: #e6e6ea;
		border-radius: 3px;
		font-size:0.85rem;
		color: #2d3047;
		text-decoration: none;
		
		&.elgg-state-selected,
		&:hover {
			color: #fff;
			background: #2d3047;
			text-decoration: none;
		}
	}
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
	border: 1px solid var(--elgg-border-color-mild);
	padding: 0.5rem;
	border-radius: 3px;
	position: relative;
	
	&:hover {
		border-color: var(--elgg-border-color-highlight);
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
		background: var(--elgg-state-danger-background-color);
		color: var(--elgg-state-danger-font-color);
		border-color: var(--elgg-state-danger-border-color);
	}
	
	.elgg-state-warning {
		background: var(--elgg-state-warning-background-color);
		color: var(--elgg-state-warning-font-color);
		border-color: var(--elgg-state-warning-border-color);
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
	border: 1px dashed var(--elgg-border-color-highlight) !important;
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

.elgg-module-plugin-details {
	width: 600px;
	min-height: 500px;
	
	.elgg-plugin {
		border: none;
		margin: 0;
		padding: 0;
	}
	
	.elgg-tabs a {
		cursor: pointer;
	}
}

/****************************************
	MARKDOWN
****************************************/
.elgg-markdown {
	margin: 15px;
	
	h1, h2, h3, h4, h5, h6 {
		margin: 1em 0 1em -15px;
		color: #333;
	}
	
	ol {
		list-style: decimal;
		padding-left: 2em;
	}
	
	ul {
		list-style: disc;
		padding-left: 2em;
	}
	
	p {
		margin: 15px 0;
	}
	
	img {
		max-width: 100%;
		height: auto;
		margin: 10px 0;
	}
	
	pre > code {
		border: none;
	}
}
