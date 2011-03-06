<?php
/**
 * Walled garden CSS
 */

$url = elgg_get_site_url();

?>
#elgg-walledgarden {
	margin: 100px auto 0 auto;
	width: 563px;
	min-height: 230px;
	background: url(<?php echo $url; ?>_graphics/walled_garden_background_top.gif) no-repeat left top;
	padding: 0;
	position: relative;
}

#elgg-walledgarden-bottom {
	margin:0 auto;
	background: url(<?php echo $url; ?>_graphics/walled_garden_background_bottom.gif) no-repeat left bottom;
	width:563px;
	height:54px;
}

#elgg-walledgarden-intro {
	width: 230px;
	float: left;
	margin: 35px 15px 15px 35px;
}

#elgg-walledgarden-login {
	width: 230px;
	float: left;
	margin: 30px 15px 45px 19px;
}

.elgg-heading-walledgarden {
	color: #666666;
	margin-top: 60px;
	line-height: 1.1em;
}

#elgg-walledgarden-lostpassword,
#elgg-walledgarden-registration {
	position: absolute;
	right: 0;
	top: 0;
	width: 563px;
	background-color: white;
	padding: 0;
	background: url(<?php echo $url; ?>_graphics/walled_garden_backgroundfull_top.gif) no-repeat left top;
	height: auto;
}

.elgg-hiddenform-body {
	padding: 30px 40px 0 40px;
	height: auto;
}
.elgg-hiddenform-bottom {
	margin: 0 auto;
	background: url(<?php echo $url; ?>_graphics/walled_garden_backgroundfull_bottom.gif) no-repeat left bottom;
	width: 563px;
	height: 54px;
	position: relative;
}
