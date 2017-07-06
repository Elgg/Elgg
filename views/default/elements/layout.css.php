//<style>
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 */

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
/* the width is on the page rather than topbar to handle small viewports */
.elgg-page-default {
	min-width: 800px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	min-height: 65px;
}
.elgg-page-default .elgg-page-navbar > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	height: auto;
}
.elgg-page-default .elgg-page-admin-notices > .elgg-inner,
.elgg-page-default .elgg-page-body > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #DEDEDE;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #424242;
	border-top: 1px solid #424242;
	border-bottom: 1px solid #000000;
	padding: 0 20px;
	position: relative;
	height: 32px;
	z-index: 9000;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 32px;
	right: 20px;
	max-width: 500px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}

<?= elgg_view('elements/components/admin_notices.css') ?>

/***** PAGE HEADER ******/
.elgg-page-header {
	padding: 5px 20px 10px;
	position: relative;
	background: #60B8F7;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}
/***** PAGE NAVBAR ******/
.elgg-page-navbar {
	padding: 0 20px;
	position: relative;
	background: #4787B8;
}
.elgg-page-navbar > .elgg-inner {
	position: relative;
}

/***** PAGE BODY LAYOUT ******/
.elgg-page-body {
	padding: 0 20px;
}

.elgg-layout {
	min-height: 360px;
}

.elgg-layout-header {
	padding-bottom: 5px;
	border-bottom: 1px solid #EBEBEB;
	margin-bottom: 10px;
	width: 100%;
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: flex-end;
}

.elgg-layout-header > h1,
.elgg-layout-header > h2,
.elgg-layout-header > h3 {
	order: 1;
	padding: 5px;
	margin-right: auto; /* force flexblox to justify to the right */
}

.elgg-layout-header > .elgg-menu-container {
	order: 2;
}

.elgg-layout-one-sidebar,
.elgg-layout-two-sidebar {
	display: flex;
	align-items: flex-start;
}
.elgg-layout-one-sidebar > .elgg-body,
.elgg-layout-two-sidebar > .elgg-body {
	flex: 1;
}
.elgg-layout-one-sidebar > .elgg-sidebar {
	float: none;
	order: 1;
}
.elgg-layout-two-sidebar > .elgg-sidebar {
	float: none;
	order: 2;
}
.elgg-layout-two-sidebar > .elgg-sidebar-alt {
	float: none;
	order: 0;
}

.elgg-layout-widgets > .elgg-widgets {
	float: right;
}
.elgg-sidebar {
	position: relative;
	padding: 32px 0 20px 30px;
	float: right;
	width: 21.212121%;
	margin: 0 0 0 30px;
	border-left: 1px solid #EBEBEB;
}
.elgg-sidebar-alt {
	position: relative;
	padding: 32px 30px 20px 0;
	float: left;
	width: 16.161616%;
	margin: 0 30px 0 0;
	border-right: 1px solid #EBEBEB;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 12px 0 10px 0;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	color: #999;
	padding: 0 10px;
	position: relative;
}

.elgg-page-footer a:hover {
	color: #666;
}

@media (max-width: 1030px) {
	.elgg-page-footer {
		padding: 0 20px;
	}
}

@media (max-width: 820px) {
	.elgg-page-default {
		min-width: 0;
	}
	.elgg-page-body {
		padding: 0;
	}
	.elgg-main {
        padding: 12px 20px 10px;
		box-sizing: border-box;
    }
	.elgg-layout-one-sidebar,
	.elgg-layout-two-sidebar {
		display: block;
	}
    .elgg-layout-one-sidebar .elgg-main,
	.elgg-layout-two-sidebar .elgg-main {
        width: 100%;
    }
	.elgg-sidebar {
		border-left: none;
		border-top: 1px solid #DCDCDC;
		border-bottom: 1px solid #DCDCDC;
		background-color: #FAFAFA;
		width: 100%;
		padding: 27px 20px 20px;
		margin: 0;
		box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05) inset;
		box-sizing: border-box;
	}
	.elgg-sidebar-alt {
		display: none;
	}
	.elgg-page-default .elgg-page-footer > .elgg-inner {
		border-top: none;
	}
}