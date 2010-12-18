<?php
/**
 * Elgg profile icon edit form
 * 
 * @package ElggProfile
 * 
 * @uses $vars['entity'] The user entity
 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
 */

// user is passed to view and set by caller (normally the page editicon)
$currentuser = get_loggedin_user();
?>
<div id="edit_profile_avatar">

<p class="margin-top"><?php echo elgg_echo('profile:profilepictureinstructions'); ?></p>

<div id="current_user_avatar">

	<label><?php echo elgg_echo('profile:currentavatar'); ?></label>
	<?php 
		
		$user_avatar = $currentuser->getIcon('medium');
		echo "<img src=\"{$user_avatar}\" alt=\"avatar\" />";

	?>

</div>

<div id="avatar_upload">
<?php
	echo elgg_view_form('avatar/upload', array('enctype' => 'multipart/form-data'), array('entity' => $currentuser));
?>
</div>
	
<div id="avatar_croppingtool">	
<label><?php echo elgg_echo('profile:profilepicturecroppingtool'); ?></label><br />

<?php echo elgg_view_form('avatar/crop', array(), array('entity' => get_loggedin_user()));
?>

</div>
</div>
