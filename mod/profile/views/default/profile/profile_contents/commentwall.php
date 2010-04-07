<?php
/**
 * Elgg profile comment wall
 */
?>
<div id="profile_content">
<?php
if(isloggedin()){
	echo elgg_view("profile/commentwall/commentwalladd");
}
echo elgg_view("profile/commentwall/commentwall", array('annotation' => $vars['comments']));
?>
</div>