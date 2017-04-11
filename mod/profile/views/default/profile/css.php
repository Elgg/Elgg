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
	float: left;
	margin-bottom: 15px;
}
.profile > .elgg-inner {
	border: 1px solid #ebebeb;
	border-radius: 3px;
	margin: 0 5px;
	display: flex;
	align-items: flex-start;
}
#profile-details {
	padding: 15px;
	flex: 1;
}

/*** ownerblock ***/
#profile-owner-block {
	width: 200px;
	border-right: 1px solid #ebebeb;
	padding: 15px;
}
#profile-owner-block .large {
	margin-bottom: 10px;
}

.profile-admin-menu-wrapper a {
	display: block;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 16px;
}
.profile-admin-menu-wrapper:before {
	content: "\00BB";
	float: left;
	padding-top: 1px;
}
.profile-admin-menu-wrapper li a {
	color: #FF0000;
	margin-bottom: 0;
}
.profile-admin-menu-wrapper a:hover {
	color: #000;
}
/*** profile details ***/
.profile-field {
	border-bottom: 1px solid #ebebeb;
	margin: 0;
	padding: 10px 0;
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
	#profile-details {
		display: block;
		float: left;
	}
}
