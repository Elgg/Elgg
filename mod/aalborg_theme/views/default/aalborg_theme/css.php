<?php
/**
 * CSS Aalborg theme
 *
 * @package AalborgTheme
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	MISC
*****************************************/
#dashboard-info {
	border: 1px solid #DCDCDC;
	margin: 0 10px 15px;
}
.elgg-sidebar input[type=text],
.elgg-sidebar input[type=password] {
	box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.1);
}
.elgg-module .elgg-list-river {
	border-top: none;
}
.elgg-module .elgg-list {
	margin-top: 0;
}
/* ***************************************
	TOPBAR MENU
*****************************************/
.elgg-menu-topbar-alt > li > a {
	padding: 5px 15px 6px;
	margin: 1px 0 0;
}
.elgg-menu-topbar-alt ul {
    position: absolute;
	display: none;
    background-color: #FFF;
	border: 1px solid #DEDEDE;
    text-align: left;
    top: 33px;
    margin-left: -100px;
    width: 180px;

	border-radius: 0 0 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}
.elgg-menu-topbar-alt li ul > li > a {
	text-decoration: none;
	padding: 10px 20px;
	background-color: #FFF;
	color: #444;
}
.elgg-menu-topbar-alt li ul > li > a:hover {
	background-color: #F0F0F0;
	color: #444;
}
.elgg-menu-topbar-alt > li:hover > ul {
	display: block;
}
.elgg-menu-item-account > a:after {
	content: "\bb";
	margin-left: 6px;
}
/* ***************************************
	RESPONSIVE
*****************************************/
html {
	font-size: 100%;
	-webkit-text-size-adjust: 100%;
	-ms-text-size-adjust: 100%;
}
.elgg-button-nav {
    display: none;
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	color: #FFF;
	float: left;
	padding: 14px 18px;
}
.elgg-button-nav:hover {
	color: #FFF;
	text-decoration: none;
	background-color: #60B8F7;
}
.elgg-button-nav .icon-bar {
    background-color: #F5F5F5;
    border-radius: 1px 1px 1px 1px;
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
    display: block;
    height: 2px;
    width: 22px;
}
.elgg-button-nav .icon-bar + .icon-bar {
    margin-top: 3px;
}
.file-photo .elgg-photo {
	max-width: 100%;
	height: auto;
}
@media (max-width: 1030px) {
	.elgg-menu-topbar-default > li:first-child a {
		margin-left: 0;
	}
	.elgg-menu-topbar-alt > li > a {
		padding-right: 0;
	}
	.elgg-page-footer {
		padding: 0 20px;
	}
}
@media (max-width: 820px) {
	.elgg-page-default {
		min-width: 0;
	}
	.elgg-layout-one-sidebar .elgg-main {
		width: 100%;
	}
	.elgg-layout-two-sidebar .elgg-main {
		width: 100%;
	}
    .elgg-layout-one-sidebar {
        width: 100%;
        float: left;
    }
    .elgg-layout-two-sidebar {
        width: 100%;
        float: left;
    }
	.elgg-sidebar {
		border-left: none;
        border-top: 1px solid #DCDCDC;
        width: 100%;
        float: left;
		padding: 27px 0 20px 0;
        margin: 0 0 10px 0;
		
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
	.elgg-sidebar-alt {
        display: none;
    }
	.elgg-menu-footer {
        float: none;
        text-align: center;
    }
	.elgg-river-item input[type=text] {
		width: 100%;
	}
	.elgg-river-item input[type=submit] {
		margin: 5px 0 0 0;
	}
	/***** CUSTOM INDEX ******/
    .elgg-col-1of2 {
        width: 100%;
    }
    .prl {
        padding-right: 0;
    }
    /***** WIDGETS ******/
	.elgg-col-1of3,
    .elgg-col-2of3,	
    #elgg-widget-col-1,
    #elgg-widget-col-2,
    #elgg-widget-col-3 {
        width: 100%;
        min-height: 0 !important;
    }
    .elgg-module-widget {
        margin: 0 0 15px;
    }
}
@media (min-width: 767px) {
    .elgg-nav-collapse {
		display: block !important;
	}
}
@media (max-width: 766px) {
	.elgg-page-header > .elgg-inner h1 {
		padding-top: 10px;
	}
	.elgg-heading-site, .elgg-heading-site:hover {
		font-size: 1.6em;
	}
    .elgg-button-nav {
        cursor: pointer;
        display: block;
    }
    .elgg-nav-collapse {
        clear: both;
		display: none;
        width: 100%;
    }
	#login-dropdown a {
		padding: 10px 18px;
	}
    .elgg-menu-site {
		float: none;
    }
	.elgg-menu-site > li > ul {
		position: static;
		display: block;
		left: 0;
		margin-left: 0;
		border: none;
		box-shadow: none;
		background: none;	
	}
	.elgg-more,
	.elgg-menu-site-more li,
	.elgg-menu-site > li > ul {
		width: auto;
	}
	.elgg-menu-site ul li {
        float: none;
        margin: 0;
    }
	.elgg-more > a {
		border-bottom: 1px solid #294E6B;
	}
    .elgg-menu-site > li {
        border-top: 1px solid #294E6B;
        clear: both;
        float: none;
        margin: 0;
    }
	.elgg-menu-site > li:first-child {
        border-top: none;
    }
	.elgg-menu-site > li > a {
		padding: 10px 18px;
	}
	.elgg-menu-site-more > li > a {
		color: #FFF;
		background: none;
		padding: 10px 18px 10px 30px;	
	}
	.elgg-menu-site-more > li:last-child > a,
	.elgg-menu-site-more > li:last-child > a:hover {
		border-radius: 0;
	}
	.elgg-menu-site-more > li.elgg-state-selected > a,
	.elgg-menu-site-more > li > a:hover {
		background-color: #60B8F7;
		color: #FFF;
	}
}
@media (max-width: 600px) {	
    .groups-profile-fields {
        float: left;
        padding-left: 0;
    }
    #profile-owner-block {
    	border-right: none;
        width: auto;
    }
    #profile-details {
        display: block;
        float: left;
    }
	#groups-tools > li {
        width: 100%;
        margin-bottom: 20px;
    }
    #groups-tools > li:nth-child(odd) {
        margin-right: 0;
    }
    #groups-tools > li:last-child {
        margin-bottom: 0;
    }	
	.elgg-menu-entity, .elgg-menu-annotation {
		margin-left: 0;
	}
	.elgg-menu-entity > li, .elgg-menu-annotation > li {
		margin-left: 0;
		margin-right: 15px;
	}
	.elgg-subtext {
		float: left;
		margin-right: 15px;
	}
}
