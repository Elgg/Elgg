<?php
/**
 * Walled garden CSS
 */

echo elgg_view('core.css');
?>

.elgg-page-walled-garden {
	margin: 0;
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
}

.elgg-page-walled-garden-background {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-image: url('graphics/walled_garden.jpg');
	background-size: cover;
	background-repeat: no-repeat;
	background-position: 50%;
	filter: blur(4px);
}

.elgg-heading-walled-garden {
	font-size: 3rem;
	line-height: 3rem;
	color: $(text-color-strong);
	text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
	font-weight: $(font-bold-weight);
}

.elgg-page-walled-garden > .elgg-inner {
	width: 30rem;
	min-height: 100%;
}

.elgg-page-walled-garden > .elgg-inner > div {
	&.elgg-page-body {
		padding: 1.5rem 3rem;
		background: #fff;
		box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3), -1px -1px 1px rgba(0, 0, 0, 0.3);
	}
	&.elgg-page-header {
		padding: 3rem 0;
	}
	&.elgg-page-footer {
		background: none;
		a {
			color: $(text-color-strong);
			text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
			font-weight: $(font-bold-weight);
		}
	}

}

.elgg-page-walled-garden .elgg-main {
	min-height: 0;
	padding: 0;
}

.elgg-heading-walled-garden a {
	text-decoration: none;
	color: inherit;
}

.elgg-page-walled-garden .elgg-form-login,
.elgg-page-walled-garden .elgg-form-account {
	max-width: none;
}

@media $(media-mobile-only) {
	.elgg-page-walled-garden {
		width: 100%;
		float: none;
	}
}

