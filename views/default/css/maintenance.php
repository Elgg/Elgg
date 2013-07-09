<?php
/**
 * Maintenance mode CSS
 */

$url = elgg_get_site_url();

?>
/* <style> /**/

.elgg-body-maintenance {
	margin: 100px auto 0 auto;
	position: relative;
	width: 530px;
}
.elgg-module-maintenance {
	position: absolute;
	top: 0;
	left: 0;
}
.elgg-module-maintenance > .elgg-head {
	height: 17px;
}
.elgg-module-maintenance > .elgg-body {
	padding: 10px 20px;
}
.elgg-module-maintenance > .elgg-foot {
	height: 17px;
}
.elgg-module-maintenance > .elgg-head {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_top.png) no-repeat left top;
}
.elgg-module-maintenance > .elgg-body {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_middle.png) repeat-y left top;
}
.elgg-module-maintenance > .elgg-foot {
	background: url(<?php echo $url; ?>_graphics/walled_garden/one_column_bottom.png) no-repeat left top;
}

body {
	font-size: 24px;
}
h1 {
	font-size: 2em;
	margin-bottom: 1em;
}
h1, h2, h3, h4, h5, h6 {
	color: #666;
}
a {
	color: #999;
}

.elgg-output {
	margin-bottom: 3em;
}

.elgg-module-maintenance-login {
	font-size: 12px;
	line-height: 1.4em;
	width: 200px;
	float: right;
	margin: 0;
	border: 1px solid #ccc;
	padding: 5px;
	border-radius: 5px;
}

fieldset > div {
	margin-bottom: 5px;
}

.elgg-button-submit {
	background-color: #666;
	border-color: #555;
}
.elgg-button-submit:hover {
	background-color: #333;
	border-color: #222;
}