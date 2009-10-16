<?php
/**
 * Add a user.
 * Form to add a new user.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>

<div class="admin_adduser_link">
	<a href="#" onclick="$('#add_user_showhide').toggle()"><?php echo elgg_echo('admin:user:adduser:label'); ?></a>
</div>
<div id="add_user_showhide" style="display:none" >
<?php echo elgg_view('account/forms/useradd', array('show_admin'=>true)); ?>
</div>