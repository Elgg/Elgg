<?php
/**
 * Elgg icons
 *
 * @package Elgg.Core
 * @subpackage UI
 */

?>
/* <style> /**/

/* ***************************************
	ICONS
*************************************** */

.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	margin: 0 2px;
}
.elgg-icon-arrow-left {
	background-position: 0 -0px;
}
.elgg-icon-arrow-right {
	background-position: 0 -18px;
}
.elgg-icon-arrow-two-head {
	background-position: 0 -36px;
}
.elgg-icon-attention-hover,
.elgg-icon-attention:hover,
:focus > .elgg-icon-attention {
	background-position: 0 -54px;
}
.elgg-icon-attention {
	background-position: 0 -72px;
}
.elgg-icon-calendar {
	background-position: 0 -90px;
}
.elgg-icon-cell-phone {
	background-position: 0 -108px;
}
.elgg-icon-checkmark-hover,
.elgg-icon-checkmark:hover,
:focus > .elgg-icon-checkmark {
	background-position: 0 -126px;
}
.elgg-icon-checkmark {
	background-position: 0 -144px;
}
.elgg-icon-clip-hover,
.elgg-icon-clip:hover,
:focus > .elgg-icon-clip {
	background-position: 0 -162px;
}
.elgg-icon-clip {
	background-position: 0 -180px;
}
.elgg-icon-cursor-drag-arrow {
	background-position: 0 -198px;
}
.elgg-icon-delete-alt-hover,
.elgg-icon-delete-alt:hover,
:focus > .elgg-icon-delete-alt {
	background-position: 0 -216px;
}
.elgg-icon-delete-alt {
	background-position: 0 -234px;
}
.elgg-icon-delete-hover,
.elgg-icon-delete:hover,
:focus > .elgg-icon-delete {
	background-position: 0 -252px;
}
.elgg-icon-delete {
	background-position: 0 -270px;
}
.elgg-icon-download-hover,
.elgg-icon-download:hover,
:focus > .elgg-icon-download {
	background-position: 0 -288px;
}
.elgg-icon-download {
	background-position: 0 -306px;
}
.elgg-icon-eye {
	background-position: 0 -324px;
}
.elgg-icon-facebook {
	background-position: 0 -342px;
}
.elgg-icon-grid-hover,
.elgg-icon-grid:hover,
:focus > .elgg-icon-grid {
	background-position: 0 -360px;
}
.elgg-icon-grid {
	background-position: 0 -378px;
}
.elgg-icon-home-hover,
.elgg-icon-home:hover,
:focus > .elgg-icon-home {
	background-position: 0 -396px;
}
.elgg-icon-home {
	background-position: 0 -414px;
}
.elgg-icon-hover-menu-hover,
.elgg-icon-hover-menu:hover,
:focus > .elgg-icon-hover-menu {
	background-position: 0 -432px;
}
.elgg-icon-hover-menu {
	background-position: 0 -450px;
}
.elgg-icon-info-hover,
.elgg-icon-info:hover,
:focus > .elgg-icon-info {
	background-position: 0 -468px;
}
.elgg-icon-info {
	background-position: 0 -486px;
}
.elgg-icon-link-hover,
.elgg-icon-link:hover,
:focus > .elgg-icon-link {
	background-position: 0 -504px;
}
.elgg-icon-link {
	background-position: 0 -522px;
}
.elgg-icon-list {
	background-position: 0 -540px;
}
.elgg-icon-lock-closed {
	background-position: 0 -558px;
}
.elgg-icon-lock-open {
	background-position: 0 -576px;
}
.elgg-icon-mail-alt-hover,
.elgg-icon-mail-alt:hover,
:focus > .elgg-icon-mail-alt {
	background-position: 0 -594px;
}
.elgg-icon-mail-alt {
	background-position: 0 -612px;
}
.elgg-icon-mail-hover,
.elgg-icon-mail:hover,
:focus > .elgg-icon-mail {
	background-position: 0 -630px;
}
.elgg-icon-mail {
	background-position: 0 -648px;
}
.elgg-icon-photo {
	background-position: 0 -666px;
}
.elgg-icon-print-alt {
	background-position: 0 -684px;
}
.elgg-icon-print {
	background-position: 0 -702px;
}
.elgg-icon-push-pin-alt {
	background-position: 0 -720px;
}
.elgg-icon-push-pin {
	background-position: 0 -738px;
}
.elgg-icon-redo {
	background-position: 0 -756px;
}
.elgg-icon-refresh-hover,
.elgg-icon-refresh:hover,
:focus > .elgg-icon-refresh {
	background-position: 0 -774px;
}
.elgg-icon-refresh {
	background-position: 0 -792px;
}
.elgg-icon-round-arrow-left {
	background-position: 0 -810px;
}
.elgg-icon-round-arrow-right {
	background-position: 0 -828px;
}
.elgg-icon-round-checkmark {
	background-position: 0 -846px;
}
.elgg-icon-round-minus {
	background-position: 0 -864px;
}
.elgg-icon-round-plus {
	background-position: 0 -882px;
}
.elgg-icon-rss {
	background-position: 0 -900px;
}
.elgg-icon-search-focus {
	background-position: 0 -918px;
}
.elgg-icon-search {
	background-position: 0 -936px;
}
.elgg-icon-settings-alt-hover,
.elgg-icon-settings-alt:hover,
:focus > .elgg-icon-settings-alt {
	background-position: 0 -954px;
}
.elgg-icon-settings-alt {
	background-position: 0 -972px;
}
.elgg-icon-settings {
	background-position: 0 -990px;
}
.elgg-icon-share-hover,
.elgg-icon-share:hover,
:focus > .elgg-icon-share {
	background-position: 0 -1008px;
}
.elgg-icon-share {
	background-position: 0 -1026px;
}
.elgg-icon-shop-cart-hover,
.elgg-icon-shop-cart:hover,
:focus > .elgg-icon-shop-cart {
	background-position: 0 -1044px;
}
.elgg-icon-shop-cart {
	background-position: 0 -1062px;
}
.elgg-icon-speech-bubble-alt-hover,
.elgg-icon-speech-bubble-alt:hover,
:focus > .elgg-icon-speech-bubble-alt {
	background-position: 0 -1080px;
}
.elgg-icon-speech-bubble-alt {
	background-position: 0 -1098px;
}
.elgg-icon-speech-bubble-hover,
.elgg-icon-speech-bubble:hover,
:focus > .elgg-icon-speech-bubble {
	background-position: 0 -1116px;
}
.elgg-icon-speech-bubble {
	background-position: 0 -1134px;
}
.elgg-icon-star-alt {
	background-position: 0 -1152px;
}
.elgg-icon-star-empty-hover,
.elgg-icon-star-empty:hover,
:focus > .elgg-icon-star-empty {
	background-position: 0 -1170px;
}
.elgg-icon-star-empty {
	background-position: 0 -1188px;
}
.elgg-icon-star-hover,
.elgg-icon-star:hover,
:focus > .elgg-icon-star {
	background-position: 0 -1206px;
}
.elgg-icon-star {
	background-position: 0 -1224px;
}
.elgg-icon-tag-hover,
.elgg-icon-tag:hover,
:focus > .elgg-icon-tag {
	background-position: 0 -1242px;
}
.elgg-icon-tag {
	background-position: 0 -1260px;
}
.elgg-icon-thumbs-down-alt-hover,
.elgg-icon-thumbs-down-alt:hover,
:focus > .elgg-icon-thumbs-down-alt {
	background-position: 0 -1278px;
}
.elgg-icon-thumbs-down-hover,
.elgg-icon-thumbs-down:hover,
:focus > .elgg-icon-thumbs-down,
.elgg-icon-thumbs-down-alt {
	background-position: 0 -1296px;
}
.elgg-icon-thumbs-down {
	background-position: 0 -1314px;
}
.elgg-icon-thumbs-up-alt-hover,
.elgg-icon-thumbs-up-alt:hover,
:focus > .elgg-icon-thumbs-up-alt {
	background-position: 0 -1332px;
}
.elgg-icon-thumbs-up-hover,
.elgg-icon-thumbs-up:hover,
:focus > .elgg-icon-thumbs-up,
.elgg-icon-thumbs-up-alt {
	background-position: 0 -1350px;
}
.elgg-icon-thumbs-up {
	background-position: 0 -1368px;
}
.elgg-icon-trash {
	background-position: 0 -1386px;
}
.elgg-icon-twitter {
	background-position: 0 -1404px;
}
.elgg-icon-undo {
	background-position: 0 -1422px;
}
.elgg-icon-user-hover,
.elgg-icon-user:hover,
:focus > .elgg-icon-user {
	background-position: 0 -1440px;
}
.elgg-icon-user {
	background-position: 0 -1458px;
}
.elgg-icon-users-hover,
.elgg-icon-users:hover,
:focus > .elgg-icon-users {
	background-position: 0 -1476px;
}
.elgg-icon-users {
	background-position: 0 -1494px;
}
.elgg-icon-video {
	background-position: 0 -1512px;
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
	min-height: 31px;
	min-width: 31px;
}

/* ***************************************
	AVATAR ICONS
*************************************** */
.elgg-avatar {
	position: relative;
	display: inline-block;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;

	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	border-radius: 3px;

	background-clip:  border;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;

	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	border-radius: 5px;

	background-clip:  border;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large {
	width: 100%;
}
.elgg-avatar-large > a > img {
	width: 100%;
	height: auto;
}
.elgg-state-banned {
	opacity: 0.5;
}