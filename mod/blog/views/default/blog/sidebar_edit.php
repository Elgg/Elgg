<?php
/**
 * Blog sidebar menu for editing / creating a blog post.
 *
 * @package Blog
 */

//If editing a post, show the previous revisions and drafts.
$blog_guid = isset($vars['blog_guid']) ? $vars['blog_guid'] : FALSE;
$blog = get_entity($blog_guid);

if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
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
}

if ($revisions) {
	echo '<ul class="blog_revisions">';
	$load_base_url = elgg_get_site_url()."pg/blog/{$owner->username}/edit/{$blog->getGUID()}/";

	foreach ($revisions as $revision) {
		$time = elgg_view_friendly_time($revision->time_created);
		$load = elgg_view('output/url', array(
			'href' => $load_base_url . $revision->id,
			'text' => elgg_echo('load')
		));


		if ($revision->name == 'blog_auto_save') {
			$name = elgg_echo('blog:auto_saved_revision');
			$text = "$name: $time $load";
			$class = 'class="auto_saved"';
		} else {
			$name = elgg_echo('blog:revision');
			$text = "$name: $time $load";
			$class = 'class="auto_saved"';

			$revision_count--;
		}

		echo <<<___END
<li $class>
$text
</li>

___END;
	}

	echo '</ul>';
}