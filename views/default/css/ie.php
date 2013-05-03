/**
 * CSS for IE8 and above
 */

/* ie8 does not like shrink wrapping this div with inline-block */
.elgg-avatar {
	display: block;
}

/* fixes issue in ie8 hovering over an avatar in a widget or group sidebar listing */
.elgg-gallery-users > .elgg-item {
	float: left;
	margin-bottom: 5px;
}

.elgg-gallery-users {
	display: inline-block;
}
