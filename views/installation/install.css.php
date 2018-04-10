<?php

elgg_set_viewtype('default');

$css = elgg_view('core.css', []);

echo _elgg_views_preprocess_css(null, null, $css, []);

elgg_set_viewtype('installation');

?>

html {
	min-width: 100vh;
	background: #0078ac;
}

body {
	background: transparent;
}

.elgg-page {
	max-width: 50rem;
	margin: 5rem auto;
	background: #fff;
	border-radius: 2px;
}

.elgg-system-messages {
	position: relative;
	top: auto;
	right: auto;
	max-width: 100%;
}

.elgg-layout-columns > .elgg-sidebar-alt {
	padding: 2rem;
}

.elgg-layout-columns > .elgg-body {
	padding: 4rem;
}

.elgg-page-header {
	margin-bottom: 2rem;
	text-align: center;
}

.elgg-sidebar-alt ol {
	list-style: decimal;
	padding-left: 2rem;
	font-size: 1rem;
}

.elgg-sidebar-alt ol > li {
	padding: 0.25rem 0.5rem;
}

.elgg-sidebar-alt ol > li.present {
	font-weight: 600;
	color: #0078ac;
}

.elgg-sidebar-alt ol > li.past {
	text-decoration: line-through;
	color: #ccc;
}

.elgg-page-footer {
	min-height: 0;
	padding: 1rem 2rem;
	overflow: hidden;
	border-radius: 0 0 2px 2px;
}

.elgg-install-nav {
	display: flex;
	justify-content: flex-end;
	margin-top: 4rem;
}

h1 {
	border-bottom: 1px solid #dcdcdc;
	padding: 0 0 1rem 0;
	margin-bottom: 1rem;
}

h2, h3 {
	margin-bottom: 0.5rem;
}
