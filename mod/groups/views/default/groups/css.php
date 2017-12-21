<?php
/**
 * Elgg Groups css
 *
 * @package groups
 */

?>
/*<style>*/
.groups-profile > .elgg-image {
	margin-right: 20px;
}

/*** group profile details ***/
.group-profile-field {
	border-bottom: 1px solid #ebebeb;
	margin: 0;
	padding: 0.5rem;
}
.group-profile-field:last-child {
	border-bottom: none;
}

/* fix for about me field */
.group-profile-field .elgg-output {
	margin: 0;
}

.elgg-listing-summary-subtitle {
	.groups-membership, .groups-members {
		margin-right: 10px;
		
		.elgg-icon {
			margin-right: 5px;
		}
	}
}

.elgg-sidebar .elgg-listing-summary-subtitle {
	.groups-membership, .groups-members {
		display: block;
	}
}

#groups-tools > li {
	width: 48%;
	margin-bottom: 40px;
	display: flex;
}

#groups-tools > li > .elgg-module {
	flex-grow: 1;
}

#groups-tools > li:nth-child(odd) {
	margin-right: 4%;
}

.groups-widget-viewall {
	float: right;
	font-size: 85%;
}

.elgg-menu-groups-my-status li a {
	color: #444;
	display: block;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 0;
}
.elgg-menu-groups-my-status li a:hover {
	color: #999;
}
.elgg-menu-groups-my-status li.elgg-state-selected > a {
	color: #999;
}

@media (max-width: 600px) {
	.groups-profile {
		display: block;
	}
	.groups-profile-fields {
		padding-top: 20px;
	}
	.profile > .elgg-inner {
		display: block;
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
}
