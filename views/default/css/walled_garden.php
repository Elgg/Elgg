<?php
/**
 * Walled garden CSS
 */

$url = elgg_get_site_url();

?>
.elgg-body-walledgarden {
	margin: 100px auto 0 auto;
	position: relative;
	width: 530px;
}
.elgg-module-walledgarden {
	position: absolute;
	top: 0;
	left: 0;
}
.elgg-module-walledgarden > .elgg-head {
	height: 17px;
}
.elgg-module-walledgarden > .elgg-body {
	padding: 0 10px;
}
.elgg-module-walledgarden > .elgg-foot {
	height: 17px;
}
.elgg-walledgarden-double > .elgg-head {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_top.png) no-repeat left top;
}
.elgg-walledgarden-double > .elgg-body {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_middle.png) repeat-y left top;
}
.elgg-walledgarden-double > .elgg-foot {
	background: url(<?php echo $url; ?>_graphics/walled_garden/two_column_bottom.png) no-repeat left top;
}
.elgg-walledgarden-single > .elgg-head {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_top.png) no-repeat left top;
}
.elgg-walledgarden-single > .elgg-body {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_middle.png) repeat-y left top;
}
.elgg-walledgarden-single > .elgg-foot {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_bottom.png) no-repeat left top;
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

.elgg-walledgarden-single > .elgg-body > .elgg-inner {
	padding: 0 8px;
}

.elgg-module-walledgarden-login {
	margin: 0;
}
.elgg-body-walledgarden h3 {
	font-size: 1.5em;
	line-height: 1.1em;
	padding-bottom: 5px;
}

.elgg-heading-walledgarden {
	color: #666666;
	margin-top: 60px;
	line-height: 1.1em;
}
