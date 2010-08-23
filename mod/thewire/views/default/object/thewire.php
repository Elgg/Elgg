<?php

	/**
	 * Elgg Wire Posts Listings
	 * 
	 * @package thewire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 *
	 * @question - do we want users to be able to edit thewire?
	 * 
	 * @uses $vars['entity'] Optionally, the note to view
	 */

if (isset($vars['entity'])) {
	$user_name = $vars['entity']->getOwnerEntity()->name;
	
	//if the note is a reply, we need some more info
	$note_url = '';
	$note_owner = elgg_echo("thewire:notedeleted");  		
?>
<div class="wire_post">
	<div class="wire_post_contents clearfloat radius8">

	    <div class="wire_post_icon">
	    <?php
		        echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'tiny'));
	    ?>
	    </div>

		<div class="wire_post_options">
		<?php
			if(isloggedin()){
		?>
			<a href="<?php echo $vars['url']; ?>mod/thewire/add.php?wire_username=<?php echo $vars['entity']->getOwnerEntity()->username; ?>" class="action_button reply small"><?php echo elgg_echo('thewire:reply'); ?></a>
    		<?php
			}//close reply if statement
			// if the user looking at thewire post can edit, show the delete link
			if ($vars['entity']->canEdit()) {
			   echo "<span class='delete_button'>" . elgg_view("output/confirmlink",array(
					'href' => $vars['url'] . "action/thewire/delete?thewirepost=" . $vars['entity']->getGUID(),
					'text' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm'),
				)) . "</span>";
			}
		?>
	    </div>
		
		<div class="wire_post_info">
			<a href="<?php echo $vars['url']; ?>pg/thewire/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $user_name; ?></a>
			<?php
			    $desc = $vars['entity']->description;
			    //$desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'pg/thewire/$1">$1</a>',$desc);
				echo parse_urls($desc);
			?>
			<p class="entity_subtext">		
			<?php
				echo elgg_echo("thewire:wired") . " " . sprintf(elgg_echo("thewire:strapline"),
								elgg_view_friendly_time($vars['entity']->time_created)
				);
				echo ' ';
				echo sprintf(elgg_echo('thewire:via_method'), elgg_echo($vars['entity']->method));
				echo '.';
			?>
			</p>		
		</div>
	</div>
</div>
<?php
}
?>