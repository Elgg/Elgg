<?php
	/**
	 * Elgg file browser.
	 * File renderer.
	 *
	 * @package ElggFile
	 */

	global $CONFIG;

	$file = $vars['entity'];

	$file_guid = $file->getGUID();
	$tags = $file->tags;
	$title = $file->title;
	$desc = $file->description;
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	$mime = $file->mimetype;

	if (!$title) {
		$title = elgg_echo('untitled');
	}

	if (elgg_get_context() == "search") { 	// Start search listing version

		if (get_input('listtype') == "gallery") {
			echo "<div class='filerepo_gallery_item'>";
			if ($vars['entity']->smallthumb) {
				echo "<p class='filerepo_title'>" . $file->title . "</p>";
				echo "<p><a href=\"{$file->getURL()}\"><img src=\"".elgg_get_site_url()."mod/file/thumbnail.php?size=medium&file_guid={$vars['entity']->getGUID()}\" border=\"0\" /></a></p>";
				echo "<p class='filerepo_timestamp'><small><a href=\"".elgg_get_site_url()."pg/file/{$owner->username}\">{$owner->username}</a> {$friendlytime}</small></p>";

				//get the number of comments
				$numcomments = elgg_count_comments($vars['entity']);
				if ($numcomments)
					echo "<p class='filerepo_comments'><a href=\"{$file->getURL()}\">" . elgg_echo("comments") . " (" . $numcomments . ")</a></p>";


				//if the user can edit, display edit and delete links
				if ($file->canEdit()) {
					echo "<div class='filerepo_controls'><p>";
					echo "<a href=\"".elgg_get_site_url()."mod/file/edit.php?file_guid={$file->getGUID()}\">" . elgg_echo('edit') . "</a>&nbsp;";
					echo elgg_view('output/confirmlink',array(

							'href' => "action/file/delete?file=" . $file->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("file:delete:confirm"),
							'is_action' => true,

						));
					echo "</p></div>";
				}


			} else {
				echo "<p class='filerepo_title'>{$title}</p>";
				echo "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid, 'size' => 'large')) . "</a>";
				echo "<p class='filerepo_timestamp'><small><a href=\"".elgg_get_site_url()."pg/file/{$owner->username}\">{$owner->name}</a> {$friendlytime}</small></p>";
				//get the number of comments
				$numcomments = elgg_count_comments($file);
				if ($numcomments)
					echo "<p class='filerepo_comments'><a href=\"{$file->getURL()}\">" . elgg_echo("comments") . " (" . $numcomments . ")</a></p>";

			}
			echo "</div>";

		} else {

			$info = "<p class='entity-title'> <a href=\"{$file->getURL()}\">{$title}</a></p>";
			$info .= "<p class='entity-subtext'><a href=\"".elgg_get_site_url()."pg/file/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
			$numcomments = elgg_count_comments($file);
			if ($numcomments)
				$info .= ", <a href=\"{$file->getURL()}\">" . elgg_echo("comments") . " (" . $numcomments . ")</a>";
			$info .= "</p>";
			$icon = "<a href=\"{$file->getURL()}\">" . elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid, 'size' => 'small')) . "</a>";

			echo elgg_view_listing($icon, $info);

		}

	} else { // Start main version

?>
	<div class="filerepo_file">
		<div class="filerepo_icon">
					<a href="<?php echo elgg_get_site_url(); ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php

						echo elgg_view("file/icon", array("mimetype" => $mime, 'thumbnail' => $file->thumbnail, 'file_guid' => $file_guid));

					?></a>
		</div>

		<div class="filerepo_title_owner_wrapper">
		<?php
			//get the user and a link to their gallery
			$user_gallery = elgg_get_site_url() . "mod/file/search.php?md_type=simpletype&subtype=file&tag=image&owner_guid=" . $owner->guid . "&listtype=gallery";
		?>
		<div class="filerepo_user_gallery_link"><a href="<?php echo $user_gallery; ?>"><?php echo elgg_echo("file:user:gallery",array('')); ?></a></div>
		<div class="filerepo_title"><h2><a href="<?php echo elgg_get_site_url(); ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo $title; ?></a></h2></div>
		<div class="filerepo_owner">
				<?php

					echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));

				?>
				<p class="filerepo_owner_details"><b><a href="<?php echo elgg_get_site_url(); ?>pg/file/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a></b><br />
				<small><?php echo $friendlytime; ?></small></p>
		</div>
		</div>


		<div class="filerepo_maincontent">

		<div class="filerepo_description"><?php echo elgg_view('output/longtext', array('value' => $desc)); ?></div>
<?php

		if (!empty($tags)) {
?>
			<p class="tags"><?php echo elgg_view('output/tags',array('value' => $tags)); ?></p>
<?php
		}

		$categories = elgg_view('categories/view',$vars);
		if (!empty($categories)) {
?>
			<p class="categories"><?php echo $categories; ?></p>
<?php
		}

?>
		<?php
			if (elgg_view_exists('file/specialcontent/' . $mime)) {
				echo "<div class='filerepo_specialcontent'>".elgg_view('file/specialcontent/' . $mime, $vars)."</div>";
			} else if (elgg_view_exists("file/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
				echo "<div class='filerepo_specialcontent'>".elgg_view("file/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default", $vars)."</div>";
			}

		?>

		<div class="filerepo_download"><p><a class="action-button small" href="<?php echo elgg_get_site_url(); ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo elgg_echo("file:download"); ?></a></p></div>

<?php

	if ($file->canEdit()) {
?>

	<div class="filerepo_controls">
				<p>
					<a href="<?php echo elgg_get_site_url(); ?>mod/file/edit.php?file_guid=<?php echo $file->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a>&nbsp;
					<?php
						echo elgg_view('output/confirmlink',array(

							'href' => "action/file/delete?file=" . $file->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("file:delete:confirm"),
							'is_action' => true,

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

	if ($vars['full']) {

		echo elgg_view_comments($file);

	}

?>

<?php

	}

?>