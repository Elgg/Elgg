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
.elgg-icon-settings {
	background-position: -302px -44px;
}
.elgg-icon-friends {
	background-position: 0 -300px;
	width: 36px;
}
.elgg-icon-friends:hover {
	background-position: 0 -340px;
}
.elgg-icon-help {
	background-position: -302px -136px;
}
.elgg-icon-delete {
	background-position: -199px 1px;
}
.elgg-icon-delete:hover {
	background-position: -199px -15px;
}
.elgg-icon-thumbs-up {
	background-position: 0px -101px;
	width: 20px;
	height: 20px;
}
.elgg-icon-thumbs-up:hover {
	background-position: 0px -131px;
}
.elgg-icon-thumbs-up-alt {
	background-position: 0px -131px;
	width: 20px;
	height: 20px;
}
.elgg-icon-arrow-s {
	background-position: -146px -56px;
}
.elgg-icon-arrow-s:hover {
	background-position: -146px -76px;
}
.elgg-icon-tag {
	background-position: 2px -197px;
}
.elgg-icon-following {
	background-position: -35px -100px;
	width: 22px;
	height: 20px;
}
.elgg-icon-rss {
	background-position: -249px 1px;
}
.elgg-icon-hover-menu {
	background-position: -150px 0;
}
.elgg-icon-hover-menu:hover {
	background-position: -150px -32px;
}
.elgg-icon-dragger {
	background-position: -302px -186px;
	width: 21px;
	height: 21px;
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
	background-color: white;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif);
	background-repeat: no-repeat;
	background-position: center center;
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
	-moz-background-clip:  border;

	-o-background-size: 25px;
	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-moz-background-clip:  border;

	-o-background-size: 40px;
	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large > a > img {
	width: 200px;
	height: 200px;
}
