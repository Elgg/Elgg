<?php
/**
 * Blog sidebar menu showing revisions
 *
 * @package Blog
 */

//If editing a post, show the previous revisions and drafts.
$blog = elgg_extract('entity', $vars, FALSE);

if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
	$owner = $blog->getOwnerEntity();
	$revisions = array();

	$auto_save_annotations = $blog->getAnnotations('blog_auto_save', 1);
	if ($auto_save_annotations) {
		$revisions[] = $auto_save_annotations[0];
	}

	// count(FALSE) == 1!  AHHH!!!
	$saved_revisions = $blog->getAnnotations('blog_revision', 10, 0, 'time_created DESC');
	if ($saved_revisions) {
		$revision_count = count($saved_revisions);
	} else {
		$revision_count = 0;
	}

	$revisions = array_merge($revisions, $saved_revisions);

	if ($revisions) {
		$title = elgg_echo('blog:revisions');

		$n = count($revisions);
		$body = '<ul class="blog-revisions">';

		$load_base_url = "blog/edit/{$blog->getGUID()}";

		// show the "published revision"
		if ($blog->status == 'published') {
			$load = elgg_view('output/url', array(
				'href' => $load_base_url,
				'text' => elgg_echo('blog:status:published')
			));

			$time = "<span class='elgg-subtext'>"
				. elgg_view_friendly_time($blog->time_created) . "</span>";

			$body .= "<li>$load : $time</li>";
		}

		foreach ($revisions as $revision) {
			$time = "<span class='elgg-subtext'>"
				. elgg_view_friendly_time($revision->time_created) . "</span>";

			if ($revision->name == 'blog_auto_save') {
				$revision_lang = elgg_echo('blog:auto_saved_revision');
			} else {
				$revision_lang = elgg_echo('blog:revision') . " $n";
			}
			$load = elgg_view('output/url', array(
				'href' => "$load_base_url/$revision->id",
				'text' => $revision_lang
			));

			$text = "$load: $time";
			$class = 'class="auto-saved"';

			$n--;

			$body .= "<li $class>$text</li>";
		}

		$body .= '</ul>';

		echo elgg_view_module('aside', $title, $body);
	}
}