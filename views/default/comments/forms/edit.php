<?php

    /**
	 * Elgg comments add form
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity']
	 */
	 
	 if (isset($vars['entity'])) {
	 
?>
 
    <form action="<?php echo $vars['url']; ?>action/comments/add" method="post">
				<h3>
					<?php echo elgg_echo("generic_comments:add"); ?>
				</h3>
				<p>
					<label><?php echo elgg_echo("generic_comments:text"); ?><br />
						<?php

							echo elgg_view('input/longtext',array('internalname' => 'generic_comment'));
						
						?>
					</label>
				</p>
				<p>
					<input type="hidden" name="entity_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /> 
					<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
				</p>
	</form>
	
<?php

    }
    
?>