/**
 * CSS for IE8 and above
 */

/* ie8 does not like shrink wrapping this div with inline-block */
.elgg-avatar {
	display: block;
}

/* ie8 adds space to the top of .elgg-gallery which causes jumpiness if this is display: block; */
.elgg-gallery .elgg-avatar > a > img {
    display: inline-block;
}
.elgg-gallery .elgg-avatar > .elgg-icon-hover-menu {
    bottom: 4px;
}
