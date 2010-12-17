<?php
/**
 * Provide a way of setting your email
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner();

if ($user) {
?>
<div class="user-settings email">
<h3><?php echo elgg_echo('email:settings'); ?></h3>
<p>
	<?php echo elgg_echo('email:address:label'); ?>:
	<?php

		echo elgg_view('input/email',array('internalname' => 'email', 'value' => $user->email));

	?>
</p>
</div>
<?php
}