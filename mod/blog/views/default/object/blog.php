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
		<h3><a href="<?php echo $vars['url']; ?>blog/<?php echo $vars['entity_owner']->username; ?>/read/<?php echo $vars['entity']->getGUID(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
		<p class="strapline">
			<span style="float:right">
			<?php

				echo sprintf(elgg_echo("blog:strapline"),
								date("F j",$vars['entity']->time_created)
				);
			
			?>
			</span>
			<a href="<?php echo $vars['url']; ?>blog/<?php echo $vars['entity_owner']->username; ?>"><?php echo $vars['entity_owner']->name; ?></a>
		</p>
		<p>
			<?php

				echo nl2br($vars['entity']->description);
			
			?>
		</p>
		<p>
			<?php

				echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));
			
			?>
		</p>
		<?php

			if ($vars['entity']->canEdit()) {
				
			?>
				<a href="<?php echo $vars['url']; ?>mod/blog/edit.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
				<?php
				
					echo elgg_view("output/confirmlink", array(
																'href' => $vars['url'] . "action.php?action=blog/delete&blogpost=" . $vars['entity']->getGUID(),
																'text' => elgg_echo('delete'),
																'confirm' => elgg_echo('deleteconfirm'),
															));

					// Allow the menu to be extended
					echo elgg_view("editmenu",array('entity' => $vars['entity']));
				
				?>
			<?php
			}
		
		?>
	</div>

<?php

		// If we've been asked to display the full view
			if (isset($vars['full']) && $vars['full'] == true) {
				echo elgg_view('object/blog-comments',array('entity' => $vars['entity'], 'comments' => $vars['comments']));
			}

		// Display comments if any
			// echo elgg_view('object/blog-comments',array('entity' => $vars['entity'], 'comments' => $vars['comments']));

		}

?>