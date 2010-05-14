<?php

/**
 * Elgg invite form contents
 *
 * @package ElggInviteFriends
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @link http://elgg.org/
 */


echo "<h2>".elgg_echo('friends:invite')."</h2>";
?>
<p class="margin_top">
<label>
	<?php echo elgg_echo('invitefriends:introduction'); ?>
<textarea class="input_textarea" name="emails" ></textarea></label></p>
<p><label>
	<?php echo elgg_echo('invitefriends:message'); ?>
<textarea class="input_textarea" name="emailmessage" ><?php
	echo sprintf(elgg_echo('invitefriends:message:default'),$CONFIG->site->name);
?></textarea></label></p>
<?php echo elgg_view('input/submit', array('value' => elgg_echo('send'))); ?>
