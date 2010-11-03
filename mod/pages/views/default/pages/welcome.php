<?php
/**
 * Elgg Pages welcome message
 *
 * @package ElggPages
 */

if($vars['entity']) {
	foreach($vars['entity'] as $welcome){
		echo "<div class=\"contentWrapper pageswelcome\">" . $welcome->description . "</div>";
	}
} else {
?>
<div class="contentWrapper pageswelcome"><p><?php echo elgg_echo('pages:welcomemessage', array($vars['config']->sitename)); ?></p></div>
<?php
}
