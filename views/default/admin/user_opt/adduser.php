<?php
	/**
	 * Add a user.
	 * Form to add a new user.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>

<div class="admin_adduser_link">
	<a href="#" onclick="$('#add_user_showhide').toggle()"><?php echo elgg_echo('admin:user:adduser:label'); ?></a>
</div>
<div id="add_user_showhide" style="display:none" >
<?php echo elgg_view('account/forms/useradd', array('show_admin'=>true)); ?>
</div>