<?php
/**
 * Blog sidebar menu showing revisions
 *
 * @package Blog
 */

//If editing a post, show the previous revisions and drafts.
$blog = isset($vars['entity']) ? $vars['entity'] : FALSE;

if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
	$owner = $blog->getOwnerEntity();
	$revisions = array();

	if ($auto_save_annotations = $blog->getAnnotations('blog_auto_save', 1)) {
		$revisions[] = $auto_save_annotations[0];
	}

	// count(FALSE) == 1!  AHHH!!!
	if ($saved_revisions = $blog->getAnnotations('blog_revision', 10, 0, 'time_created DESC')) {
		$revision_count = count($saved_revisions);
	} else {
		$revision_count = 0;
	}

	$revisions = array_merge($revisions, $saved_revisions);

	if ($revisions) {
		echo '<h3>' . elgg_echo('blog:revisions') . '</h3>';

		$n = count($revisions);
		echo '<ul class="blog_revisions">';

		$load_base_url = "pg/blog/{$owner->username}/edit/{$blog->getGUID()}/";

		// show the "published revision"
		if ($blog->status == 'published') {
			$load = elgg_view('output/url', array(
				'href' => $load_base_url,
				'text' => elgg_echo('blog:status:published')
			));

			$time = "<span class='entity-subtext'>".elgg_view_friendly_time($blog->publish_date)."</span>";

			echo '<li>
			' . $load . ": $time
			</li>";
		}

		foreach ($revisions as $revision) {
			$time = "<span class='entity-subtext'>".elgg_view_friendly_time($revision->time_created)."</span>";
			if ($revision->name == 'blog_auto_save') {
				$revision_lang = elgg_echo('blog:auto_saved_revision');
			} else {
				$revision_lang = elgg_echo('blog:revision') . " $n";
			}
			$load = elgg_view('output/url', array(
				'href' => $load_base_url . $revision->id,
				'text' => $revision_lang
			));

			$text = "$load: $time";
			$class = 'class="auto_saved"';

			$n--;

			echo <<<___END
<li $class>
$text
</li>

___END;
		}

		echo '</ul>';
	}
}