<?php

	/**
	 * Elgg thewire note view
	 * 
	 * @package ElggTheWire
	 *
	 * @question - do we want users to be able to edit thewire?
	 * 
	 * @uses $vars['entity'] Optionally, the note to view
	 */

	if (isset($vars['entity'])) {
    		
    		$user = $vars['entity']->getOwnerEntity();
			$user_url = "{$vars['url']}pg/thewire/owner/{$user->username}";
			$user_link = elgg_view('output/url', array('href' => $user_url, 'text' => $user->name));
    		
    		//if the note is a reply, we need some more info
    		
			$note_url = '';
			$note_owner = elgg_echo("thewire:notedeleted");
    		
?>
<div class="thewire-singlepage">
	<div class="thewire-post">
			    
	    <!-- the actual shout -->
		<div class="note_body">

	    <div class="thewire_icon">
	    <?php
		        echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'small'));
	    ?>
	    </div>

			<div class="thewire_options">
<?php
	if (isloggedin()) {
?>
			<a href="<?php echo $vars['url']; ?>pg/thewire/reply/<?php echo $vars['entity']->getOwnerEntity()->username; ?>" class="reply"><?php echo elgg_echo('thewire:reply'); ?></a>
		<?php
	}
/*		    //only have a reply option for main notes, not other replies
		    if($vars['entity']->parent == 0){
        ?>
		<a href="<?php echo $vars['url']; ?>mod/thewire/reply.php?note_id=<?php echo $vars['entity']->guid; ?>" class="reply">reply</a>
		<?php
	        }
*/
	    ?>
	    <div class="clearfloat"></div>
	    		<?php
				   
			// if the user looking at thewire post can edit, show the delete link
			if ($vars['entity']->canEdit()) {
						
	  
					   echo "<div class='delete_note'>" . elgg_view("output/confirmlink",array(
															'href' => $vars['url'] . "action/thewire/delete?thewirepost=" . $vars['entity']->getGUID(),
															'text' => elgg_echo('delete'),
															'confirm' => elgg_echo('deleteconfirm'),
														)) . "</div>";
			
			} //end of can edit if statement
		?>
	    </div>
	    
			<div class="note_text">
		<?php
		    echo "<b>{$user_link}: </b>";
		    

		    $desc = $vars['entity']->description;

		    $desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'pg/thewire/owner/$1">$1</a>',$desc);
			echo parse_urls($desc);
		?>
			</div>
		
		<div class="clearfloat"></div>
		</div>
		<div class="note_date">
		
		<?php
			
				echo elgg_echo("thewire:wired") . " " . sprintf(elgg_echo("thewire:strapline"),
								elgg_view_friendly_time($vars['entity']->time_created)
				);

				echo ' ';
				echo sprintf(elgg_echo('thewire:via_method'), elgg_echo($vars['entity']->method));
				echo '.';
		?>
		</div>
		
		
	</div>
</div>
<?php

		}

?>