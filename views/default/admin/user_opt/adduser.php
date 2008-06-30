<?php
	/**
	 * Add a user.
	 * Form to add a new user.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
?>

<div>
	<?php echo elgg_echo('admin:user:adduser:label'); ?>
</div>
<div id="add_user_showhide">
<?php echo elgg_view('account/forms/useradd', array('show_admin'=>true)); ?>
</div>