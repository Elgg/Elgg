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
.groups-stats {
	margin-top: 10px;
}
.groups-stats p {
	margin-bottom: 2px;
}
.groups-profile-fields div:first-child {
	padding-top: 0;
}

.groups-profile-fields > div {
	border-bottom: 1px solid #DCDCDC;
	padding: 5px 0;
	margin-bottom: 0;
}

.groups-profile-fields .elgg-output {
	margin: 0;
}

#groups-tools > li {
	width: 48%;
	min-height: 200px;
	margin-bottom: 40px;
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
