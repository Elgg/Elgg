/* <style> /**/
	
/**
 * Navigation
 */


/* ***************************************
	ANCHORS & BADGES
*************************************** */

.elgg-anchor * {
	display: inline;
}

.elgg-anchor .elgg-icon {
	color: inherit;
	font-size: inherit;
}

.elgg-anchor img {
	vertical-align: middle;
}

.elgg-anchor * + .elgg-anchor-label {
	margin-left: 5px;
}

.elgg-anchor.elgg-friendly-time {
    color: inherit;
}

.elgg-menu-topbar .elgg-badge {
	background-color: #f00;
	font-weight: 700;
	box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
/*	font-size: 10px;*/
	position: absolute;
	top: 0;
	right: 0;
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
    margin: 0;
	padding: 0;
    background: none;
}

.elgg-breadcrumbs .nav-link {
	display: inline-block;
}

/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 15px auto;
}
/* ***************************************
	MENUS
*************************************** */
.elgg-menu-extras .nav-link,
.elgg-menu-entity .nav-link,
.elgg-menu-river .nav-link,
.elgg-menu-annotations .nav-link,
.elgg-breadcrumbs .nav-link,
.elgg-menu-widget .nav-link {
	font-size: 0.9em;
	padding: 0.25em 0.5em;
	color: inherit;
}
.elgg-menu-hz .elgg-menu-item-actions > .elgg-anchor > .elgg-anchor-label {
    display: none;
}
.elgg-menu-item-has-dropdown .elgg-child-menu {
	display: none;
}

.elgg-menu-item-has-toggle > .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-item-has-toggle.elgg-state-selected > .elgg-child-menu {
	display: block;
}

.elgg-menu > li {
	position: relative;
}
.elgg-menu > li > a .elgg-icon.elgg-state-opened,
.elgg-menu > li > a .elgg-icon.elgg-state-closed {
    font-size: 9px;
    color: inherit;
    line-height: inherit;
    margin: 0 5px;
}

.elgg-menu > li > .elgg-menu-opened .elgg-icon.elgg-state-opened {
	display: inline-block;
}
.elgg-menu > li > .elgg-menu-opened .elgg-icon.elgg-state-closed {
	display: none;
}
.elgg-menu > li > .elgg-menu-closed .elgg-icon.elgg-state-closed {
	display: inline-block;
}
.elgg-menu > li > .elgg-menu-closed .elgg-icon.elgg-state-opened {
	display: none;
}

.elgg-child-menu .elgg-anchor-icon {
	width: 25px;
    text-align: center;
    display: inline-block;
}

.elgg-menu .elgg-anchor-icon + * {
	margin-left: 5px;
}

.elgg-menu a:hover .elgg-icon {
	color: inherit;
}

.elgg-menu-login-container {
    border-top: 1px solid #ddd;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar-alt {
    order: 10;
}
.elgg-menu-topbar-default {
    order: 8;
}

.elgg-menu-topbar-tools {
	order: 1;
    margin-right: auto;
}

.elgg-menu-topbar-default .elgg-anchor-label {
	display: none;
}
.elgg-menu-topbar-alt .elgg-menu-item-account > .elgg-anchor-label {
	display: none;
}

/* temp fix until Bootstrap fixes it */
.elgg-topbar-child-menu a {
    color: inherit;
}
.navbar-inverse .navbar-nav .dropdown-menu .nav-link {
    color: inherit;
}

/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	flex-wrap: wrap;
}
.elgg-menu-title > li {
	display: inline-block;
	padding: 2.5px;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page + .elgg-menu-page {
	margin-top: 5px;
}
.elgg-menu-page .elgg-child-menu {
    list-style: none;
    padding: 0;
    width: 100%;
}
.elgg-menu-page .elgg-menu-closed:before, 
.elgg-menu-page .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\25B8";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\25BE";
}
.elgg-menu-page > li {
	padding: 0;
}

/* ***************************************
	HOVER MENU
*************************************** */

.elgg-menu-hover {
    display: none;
    min-width: 10rem;
    min-height: 4rem;
}
.elgg-menu-hover-admin a {
	color: #FF0000;
}
.elgg-menu-hover-admin a:hover {
	color: #FFF;
	background-color: #FF0000;
}

/* ***************************************
	SITE FOOTER
*************************************** */
.elgg-menu-footer a,
.elgg-menu-footer span {
	color: inherit;
}

.elgg-menu-footer-default {
	float: right;
}

.elgg-menu-footer-alt {
	float: left;
}

.elgg-menu-footer-meta {
	float: left;
}

/* ***************************************
	GENERAL MENU
*************************************** */
.elgg-menu-general {
	color: #aaa;
}

/* ***************************************
	ENTITY AND ANNOTATION
*************************************** */
.elgg-menu-entity-container,
.elgg-menu-annotation-container,
.elgg-menu-river-container {
	float: right;
	margin-left: 15px;
	color: #aaa;
}

/* ***************************************
	LONGTEXT
*************************************** */
.elgg-menu-longtext {
	float: right;
}
.elgg-field-label + .elgg-field-input .elgg-menu-longtext {
	margin-top: -35px;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras .elgg-menu-item-rss .elgg-anchor-label {
	display: none;
}

/* ***************************************
	ENTITY IMPRINT
*************************************** */
.elgg-profile-layout-header .elgg-menu-entity-imprint {
	margin: 2em auto;
}
.elgg-menu-entity-imprint .elgg-icon + * {
    margin-left: 5px;
}

.elgg-menu-entity-imprint .nav-link {
	font-size: 0.9em;
	padding: 0 1em 0 0;
	color: inherit;
}