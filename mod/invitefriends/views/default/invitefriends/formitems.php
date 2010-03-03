<?php

	/**
	 * Elgg invite page
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://elgg.org/
	 */

?>

<div class="contentWrapper notitle">
<p><label>
	<?php echo elgg_echo('invitefriends:introduction'); ?>
</label>
<textarea class="input-textarea" name="emails" ></textarea></p>
<p><label>
	<?php echo elgg_echo('invitefriends:message'); ?>
</label>
<textarea class="input-textarea" name="emailmessage" ><?php

	echo sprintf(elgg_echo('invitefriends:message:default'),$CONFIG->site->name);

?></textarea></p>
<?php

	echo elgg_view('input/submit', array('value' => elgg_echo('send')));

?>
</div>