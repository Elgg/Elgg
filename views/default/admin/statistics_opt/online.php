<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// users online
get_context('search');
$users_online = get_online_users();
get_context('admin');
?>

<div class="admin_users_online">
	<h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
	<?php echo $users_online; ?>
</div>
