<?php
/**
 * Walled garden CSS
 */

$url = elgg_get_site_url();

?>
.elgg-module-walledgarden {
	margin: 100px auto 0 auto;
	position: relative;
	width: 530px;
}
.elgg-module-walledgarden > .elgg-head {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_top.png) no-repeat left top;
	height: 17px;
}
.elgg-module-walledgarden > .elgg-body {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_middle.png) repeat-y left top;
	padding: 0 10px;
}
.elgg-module-walledgarden > .elgg-foot {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_bottom.png) no-repeat left top;
	height: 17px;
}
.elgg-col > .elgg-inner {
	margin: 0 0 0 5px;
}
.elgg-col:first-child > .elgg-inner {
	margin: 0 5px 0 0;
}
.elgg-col > .elgg-inner {
	padding: 0 8px;
}

.elgg-module-walledgarden-login {
	margin: 0;
}
.elgg-module-walledgarden-login h3 {
	font-size: 1.5em;
	line-height: 1.1em;
	padding-bottom: 5px;
}

.elgg-heading-walledgarden {
	color: #666666;
	margin-top: 60px;
	line-height: 1.1em;
}
