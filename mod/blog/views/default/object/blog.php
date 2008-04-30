<?php

	/**
	 * Elgg blog individual post view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] Optionally, the blog post to view
	 */

		if (isset($vars['entity'])) {
			
?>

	<div class="blog-post">
		<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
		<p class="strapline">
			<?php

				echo sprintf(elgg_echo("blog:strapline"),
								date("F j",$vars['entity']->time_created)
				);
			
			?>
		</p>
		<p style="float: left">
			<?php
				echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'medium'));
			?><br />
			<a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $vars['entity']->getOwnerEntity()->name; ?></a>
		</p>
		<p style="margin-left: 110px; min-height: 110px">
			<?php
		
						echo nl2br($vars['entity']->description);
			
			?>
		</p>
		<p style="margin-left: 110px">
			<?php

				echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));
			
			?>
		</p>
		<p style="margin-left: 110px">
		<?php

			if ($vars['entity']->canEdit()) {
				
			?>
				<a href="<?php echo $vars['url']; ?>mod/blog/edit.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
				<?php
				
					echo elgg_view("output/confirmlink", array(
																'href' => $vars['url'] . "action_handler.php?action=blog/delete&blogpost=" . $vars['entity']->getGUID(),
																'text' => elgg_echo('delete'),
																'confirm' => elgg_echo('deleteconfirm'),
															));

					// Allow the menu to be extended
					echo elgg_view("editmenu",array('entity' => $vars['entity']));
				
				?>
			<?php
			}
		
		?>
		</p>
	</div>

<?php

		// If we've been asked to display the full view
			if (isset($vars['full']) && $vars['full'] == true) {
				echo elgg_view('object/blog-comments',array('entity' => $vars['entity'], 'comments' => $vars['entity']->getAnnotations('comment')));
			}

		// Display comments if any
			// echo elgg_view('object/blog-comments',array('entity' => $vars['entity'], 'comments' => $vars['comments']));

		}

?>