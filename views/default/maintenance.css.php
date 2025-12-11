<?php
/**
 * Maintenance mode CSS
 */

echo elgg_view('core.css', $vars);

?>
/* <style> /**/

.elgg-heading-maintenance {
	font-size: 3rem;
	line-height: 3rem;
	color: var(--elgg-text-color-strong);
	text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
	font-weight: var(--elgg-font-bold-weight);
}

.elgg-page-maintenance {
	margin: 0;
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	
	> .elgg-inner {
		min-width: 30rem;
		max-width: 50%;
		min-height: 100%;
		
		> .elgg-page-body {
			padding: 0 1rem;
			background: #fff;
			box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3), -1px -1px 1px rgba(0, 0, 0, 0.3);
			
			.elgg-layout {
				min-height: auto;
			}
			
			a {
				text-decoration: underline;
			}
		}
		
		> .elgg-page-header {
			padding: 3rem 0;
		}
	}
	
	a {
		text-decoration: none;
		color: inherit;
	}
	
	.elgg-module {
		margin: 0;
	}
	
	.elgg-form-login,
	.elgg-form-account {
		max-width: none;
	}
}

.elgg-page-maintenance-background {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-image: var(--elgg-maintenance-background-image);
	background-size: cover;
	background-repeat: no-repeat;
	background-position: 50%;
	background-attachment: fixed;
	filter: blur(4px);
}
