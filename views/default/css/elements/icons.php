<?php
/**
 * Elgg icons
 *
 * @package Elgg.Core
 * @subpackage UI
 */

?>

/* ***************************************
	ICONS
*************************************** */

.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	display: inline-block;
	margin: 0 2px;
}
.elgg-icon-calendar {
	background-position: 0 -0px;
}
.elgg-icon-checkmark {
	background-position: 0 -18px;
}
.elgg-icon-cursor-drag-arrow {
	background-position: 0 -36px;
}
.elgg-icon-delete-alt:hover {
	background-position: 0 -54px;
}
.elgg-icon-delete-alt {
	background-position: 0 -72px;
}
.elgg-icon-delete:hover {
	background-position: 0 -90px;
}
.elgg-icon-delete {
	background-position: 0 -108px;
}
.elgg-icon-download {
	background-position: 0 -126px;
}
.elgg-icon-facebook {
	background-position: 0 -144px;
}
.elgg-icon-home {
	background-position: 0 -162px;
}
.elgg-icon-mail-empty {
	background-position: 0 -180px;
}
.elgg-icon-mail-full {
	background-position: 0 -198px;
}
.elgg-icon-print {
	background-position: 0 -216px;
}
.elgg-icon-push-pin {
	background-position: 0 -234px;
}
.elgg-icon-redo {
	background-position: 0 -252px;
}
.elgg-icon-refresh {
	background-position: 0 -270px;
}
.elgg-icon-round-arrow-left {
	background-position: 0 -288px;
}
.elgg-icon-round-arrow-right {
	background-position: 0 -306px;
}
.elgg-icon-round-checkmark {
	background-position: 0 -324px;
}
.elgg-icon-round-minus {
	background-position: 0 -342px;
}
.elgg-icon-round-plus {
	background-position: 0 -360px;
}
.elgg-icon-rss {
	background-position: 0 -378px;
}
.elgg-icon-search {
	background-position: 0 -396px;
}
.elgg-icon-settings-alt:hover {
	background-position: 0 -414px;
}
.elgg-icon-settings-alt {
	background-position: 0 -432px;
}
.elgg-icon-settings {
	background-position: 0 -450px;
}
.elgg-icon-shop-cart {
	background-position: 0 -468px;
}
.elgg-icon-speech-bubble-alt:hover {
	background-position: 0 -486px;
}
.elgg-icon-speech-bubble-alt {
	background-position: 0 -504px;
}
.elgg-icon-speech-bubble:hover {
	background-position: 0 -522px;
}
.elgg-icon-speech-bubble {
	background-position: 0 -540px;
}
.elgg-icon-star-fav-empty {
	background-position: 0 -558px;
}
.elgg-icon-star-fav {
	background-position: 0 -576px;
}
.elgg-icon-tag {
	background-position: 0 -594px;
}
.elgg-icon-thumbs-down-alt:hover {
	background-position: 0 -612px;
}
.elgg-icon-thumbs-down-alt {
	background-position: 0 -630px;
}
.elgg-icon-thumbs-down {
	background-position: 0 -648px;
}
.elgg-icon-thumbs-up-alt:hover {
	background-position: 0 -666px;
}
.elgg-icon-thumbs-up-alt,
.elgg-icon-thumbs-up:hover {
	background-position: 0 -684px;
}
.elgg-icon-thumbs-up {
	background-position: 0 -702px;
}
.elgg-icon-trash {
	background-position: 0 -720px;
}
.elgg-icon-twitter {
	background-position: 0 -738px;
}
.elgg-icon-undo {
	background-position: 0 -756px;
}
.elgg-icon-user:hover {
	background-position: 0 -774px;
}
.elgg-icon-user {
	background-position: 0 -792px;
}
.elgg-icon-users:hover {
	background-position: 0 -810px;
}
.elgg-icon-users {
	background-position: 0 -828px;
}

.elgg-avatar > .elgg-icon-hover-menu {
	display: none;
	position: absolute;
	right: 0;
	bottom: 0;
	margin: 0;
	cursor: pointer;
}

.elgg-ajax-loader {
	background: white url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif) no-repeat center center;
	min-height: 33px;
	min-width: 33px;
}

/* ***************************************
	AVATAR ICONS
*************************************** */
.elgg-avatar {
	position: relative;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
	-o-background-size: 25px;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
	-o-background-size: 40px;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large > a > img {
	width: 200px;
	height: 200px;
}
