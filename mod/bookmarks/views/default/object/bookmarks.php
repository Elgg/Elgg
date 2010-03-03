<?php

	/**
	 * Elgg bookmark view
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = friendly_time($vars['entity']->time_created);

	if (get_context() == "search") {

		if (get_input('search_viewtype') == "gallery") {

			$parsed_url = parse_url($vars['entity']->address);
			$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";
		
			$info = "<p class=\"shares_gallery_title\">". elgg_echo("bookmarks:shared") .": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a> (<a href=\"{$vars['entity']->address}\">".elgg_echo('bookmarks:visit')."</a>)</p>";
			$info .= "<p class=\"shares_gallery_user\">By: <a href=\"{$vars['url']}pg/bookmarks/{$owner->username}\">{$owner->name}</a> <span class=\"shared_timestamp\">{$friendlytime}</span></p>";
			$numcomments = elgg_count_comments($vars['entity']);
			if ($numcomments)
				$info .= "<p class=\"shares_gallery_comments\"><a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $numcomments . ")</a></p>";
			
			//display 
			echo "<div class=\"share_gallery_view\">";
			echo "<div class=\"share_gallery_info\">" . $info . "</div>";
			echo "</div>";


		} else {

			$parsed_url = parse_url($vars['entity']->address);
			$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";
			if (@file_exists($faviconurl)) {
				$icon = "<img src=\"{$faviconurl}\" />";
			} else {
				$icon = elgg_view(
					"profile/icon", array(
										'entity' => $owner,
										'size' => 'small',
									  )
				);
			}
		
			$info = "<p class=\"shares_gallery_title\">". elgg_echo("bookmarks:shared") .": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a> (<a href=\"{$vars['entity']->address}\">".elgg_echo('bookmarks:visit')."</a>)</p>";
			$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/bookmarks/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
			$numcomments = elgg_count_comments($vars['entity']);
			if ($numcomments)
				$info .= ", <a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $numcomments . ")</a>";
		    $info .= "</p>";
			echo elgg_view_listing($icon, $info);

		}
		
	} else {

?>
	<?php echo elgg_view_title(elgg_echo('bookmarks:shareditem'), false); ?>
	<div class="contentWrapper">
	<div class="sharing_item">
	
		<div class="sharing_item_title">
			<h3>
				<a href="<?php echo $vars['entity']->address; ?>"><?php echo $vars['entity']->title; ?></a>
			</h3>
		</div>
		<div class="sharing_item_owner">
			<p> 
				<b><a href="<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a></b> 
				<?php echo $friendlytime; ?>
			</p>
		</div>
		<div class="sharing_item_description">
				<?php echo elgg_view('output/longtext', array('value' => $vars['entity']->description)); ?>
		</div>
<?php

	$tags = $vars['entity']->tags;
	if (!empty($tags)) {

?>
		<div class="sharing_item_tags">
			<p>
				<?php echo elgg_view('output/tags',array('value' => $vars['entity']->tags)); ?>
			</p>
		</div>
<?php

	}

?>
		<div class="sharing_item_address">
			<p>
				<?php 

					//echo elgg_view('output/url',array('value' => $vars['entity']->address));
				
				?>
				<a href="<?php echo $vars['entity']->address; ?>"><?php echo elgg_echo('bookmarks:visit'); ?></a>
			</p>
		</div>		
		<?php

			if ($vars['entity']->canEdit()) {
		
		?>
		<div class="sharing_item_controls">
			<p>
				<a href="<?php echo $vars['url']; ?>mod/bookmarks/add.php?bookmark=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a> &nbsp; 
				<?php 
						echo elgg_view('output/confirmlink',array(
						
							'href' => $vars['url'] . "action/bookmarks/delete?bookmark_guid=" . $vars['entity']->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("bookmarks:delete:confirm"),
						
						));  
					?>
			</p>
		</div>
		<?php

			}
		
		?>
	
	</div>
	</div>
<?php

	if ($vars['full'])
		echo elgg_view_comments($vars['entity']);

?>
	
<?php

	}

?>