<?php

t/**
	 * Elgg Pages welcome message
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 if($vars['entity']){
t	 
t	 foreach($vars['entity'] as $welcome){
t	 
t	techo "<div class=\"contentWrapper pageswelcome\">" . $welcome->description . "</div>";
t	t
	t}
t	 
	 } else {

?>

<div class="contentWrapper pageswelcome"><p><?php echo sprintf(elgg_echo('pages:welcomemessage'), $vars['config']->sitename); ?></p></div>
t
<?php
t}
?>
