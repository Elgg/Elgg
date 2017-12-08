<?php
/**
 * Elgg Profile CSS
 *
 * @package Profile
 */
?>
/* <style> /**/
/* ***************************************
	Profile
*************************************** */
.profile {
	margin-bottom: 1rem;
}
.profile > .elgg-inner {
	border: 1px solid #ebebeb;
	border-radius: 3px;
	display: flex;
	align-items: stretch;
}
#profile-details {
	padding: 1rem;
	flex: 1;
}

/*** ownerblock ***/
#profile-owner-block {
	width: 13rem;
	border-right: 1px solid #ebebeb;
	padding: 1rem;
}
#profile-owner-block .elgg-avatar-large {
	margin-bottom: 1rem;
}

.profile-admin-menu-wrapper > li > a,
.profile-admin-menu > li > a {
	color: #d33f49;
	display: block;
}

/*** profile details ***/
.profile-field {
	border-bottom: 1px solid #ebebeb;
	margin: 0;
	padding: 0.5rem;
}
.profile-field:last-child {
	border-bottom: none;
}

/* fix for about me field */
.profile-field .elgg-output {
	margin: 0;
}

@media (max-width: 600px) {
	#profile-owner-block {
		border-right: none;
		width: auto;
	}
}
