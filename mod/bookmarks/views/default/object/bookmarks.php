<?php

	/**
	 * Elgg bookmark view
	 *
	 * @package ElggBookmarks
	 */

	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	$address = $vars['entity']->address;

	// you used to be able to add without titles, which created unclickable bookmarks
	// putting a fake title in so you can click on it.
	if (!$title = $vars['entity']->title) {
		$title = elgg_echo('bookmarks:no_title');
	}

	$a_tag_visit = filter_tags("<a href=\"{$address}\">" . elgg_echo('bookmarks:visit') . "</a>");
	$a_tag_title = filter_tags("<a href=\"{$address}\">$title</a>");

	if (get_context() == "search") {

		if (get_input('search_viewtype') == "gallery") {

			$parsed_url = parse_url($address);
			$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";

			$info = "<p class=\"shares_gallery_title\">". elgg_echo("bookmarks:shared") . ": <a href=\"{$vars['entity']->getURL()}\">$title</a> ($a_tag_visit)</p>";
			$info .= "<p class=\"shares_gallery_user\">By: <a href=\"{$vars['url']}pg/bookmarks/owner/{$owner->username}\">{$owner->name}</a> <span class=\"shared_timestamp\">{$friendlytime}</span></p>";
			$numcomments = elgg_count_comments($vars['entity']);
			if ($numcomments)
				$info .= "<p class=\"shares_gallery_comments\"><a href=\"{$vars['entity']->getURL()}\">".sprintf(elgg_echo("comments")). " (" . $numcomments . ")</a></p>";

			//display
			echo "<div class=\"share_gallery_view\">";
			echo "<div class=\"share_gallery_info\">" . $info . "</div>";
			echo "</div>";


		} else {

			$parsed_url = parse_url($address);
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

			$info = "<p class=\"shares_gallery_title\">". elgg_echo("bookmarks:shared") .": <a href=\"{$vars['entity']->getURL()}\">{$title}</a> ($a_tag_visit)</p>";
			$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/bookmarks/owner/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
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
				<?php echo $a_tag_title; ?>
			</h3>
		</div>
		<div class="sharing_item_owner">
			<p>
				<b><a href="<?php echo $vars['url']; ?>pg/bookmarks/owner/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a></b>
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
				<?php echo $a_tag_visit; ?>
			</p>
		</div>
		<?php

			if ($vars['entity']->canEdit()) {

		?>
		<div class="sharing_item_controls">
			<p>
				<a href="<?php echo $vars['url']; ?>pg/bookmarks/edit/<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a> &nbsp;
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