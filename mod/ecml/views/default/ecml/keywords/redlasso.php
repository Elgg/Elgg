<?php
/**
 * ECML Red Lasso support
 *
 * @package ECML
 */

$vid = (isset($vars['id'])) ? $vars['id'] : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 390;
$height = (isset($vars['height'])) ? $vars['height'] : 320;

if ($vid) {
	echo "
<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"$width\" height=\"$height\" id=\"Redlasso\">
	<param name=\"movie\" value=\"http://player.redlasso.com/redlasso_player_b1b_deploy.swf\" />
	<param name=\"flashvars\" value=\"embedId=$vid&pid=\" />
	<param name=\"allowScriptAccess\" value=\"always\" />
	<param name=\"allowFullScreen\" value=\"true\" />
	<embed src=\"http://player.redlasso.com/redlasso_player_b1b_deploy.swf\" flashvars=\"embedId=$vid&pid=\" width=\"$width\" height=\"$height\" type=\"application/x-shockwave-flash\" allowScriptAccess=\"always\" allowFullScreen=\"true\" name=\"Redlasso\">
	</embed>
</object>
";
}