<?php
	/**
	 * Provide a way of setting your default access
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	if ($vars['config']->allow_user_default_access) {
		$user = page_owner_entity();
		
		if ($user) {
			if (false === ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
				$default_access = $vars['config']->default_access;
			}
	?>
		<h3><?php echo elgg_echo('default_access:settings'); ?></h3>
		<p>
			<?php echo elgg_echo('default_access:label'); ?>:
			<?php
	
				echo elgg_view('input/access',array('internalname' => 'default_access', 'value' => $default_access));
			
			?> 
		</p>

<?php }} ?>