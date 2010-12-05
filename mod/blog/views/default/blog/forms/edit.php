<?php
/**
 * Edit blog form
 *
 * @package Blog
 */

// input names => defaults
$values = array(
	'title' => NULL,
	'description' => NULL,
	'status' => 'published',
	'publish_date' => NULL,
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => NULL,
	'tags' => NULL,
	'container_guid' => NULL,
	'guid' => NULL
);

$forward = $_SERVER['HTTP_REFERER'];

$action_buttons = '';
$delete_link = '';
$draft_warning = '';

// if entity is set, we're editing.
if (isset ($vars['entity'])) {
	$blog = $vars['entity'];

	if (elgg_instanceof($blog, 'object', 'blog')) {
		// passed in values override sticky values in input views
		// if in a sticky form, don't send the overrides and let the view figure it out.
		//if (!elgg_is_sticky_form()) {
			foreach (array_keys($values) as $field) {
				$values[$field] = $blog->$field;
			}

			// load the revision annotation if requested
			if (isset($vars['revision']) && $vars['revision'] instanceof ElggAnnotation && $vars['revision']->entity_guid == $blog->getGUID()) {
				$revision = $vars['revision'];
				$values['description'] = $vars['revision']->value;
			}

			// display a notice if there's an autosaved annotation
			// and we're not editing it.
			if ($auto_save_annotations = $blog->getAnnotations('blog_auto_save', 1)) {
				$auto_save = $auto_save_annotations[0];
			} else {
				$auto_save == FALSE;
			}

			if ($auto_save && $auto_save->id != $revision->id) {
				$draft_warning = '<span class="message warning">'
					. elgg_echo('blog:messages:warning:draft')
					. '</span>';
			}

		//}
	} else {
		echo elgg_echo('blog:error:post_not_found');
		return FALSE;
	}

	// add a delete button if editing
	$delete_url = "action/blog/delete?guid={$blog->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'action-button disabled'
	));
}

$save_button = elgg_view('input/submit', array('value' => elgg_echo('save')));
$action_buttons = $save_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'internalname' => 'title',
	'internalid' => 'blog_title',
	'value' => $values['title']
));

$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_input = elgg_view('input/text', array(
	'internalname' => 'excerpt',
	'internalid' => 'blog_excerpt',
	'value' => html_entity_decode($values['excerpt'], ENT_COMPAT, 'UTF-8')
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
	'internalname' => 'description',
	'internalid' => 'blog_description',
	'value' => $values['description']
));

$save_status = elgg_echo('blog:save_status');
if ($values['publish_date']) {
	$saved = date('F j, Y @ H:i', $values['publish_date']);
} else {
	$saved = elgg_echo('blog:never');
}

$status_label = elgg_echo('blog:status');
$status_input = elgg_view('input/pulldown', array(
	'internalname' => 'status',
	'internalid' => 'blog_status',
	'value' => $values['status'],
	'options_values' => array(
		'draft' => elgg_echo('blog:status:draft'),
		'published' => elgg_echo('blog:status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/pulldown', array(
	'internalname' => 'comments_on',
	'internalid' => 'blog_comments_on',
	'value' => $values['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'internalname' => 'tags',
	'internalid' => 'blog_tags',
	'value' => $values['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'internalname' => 'access_id',
	'internalid' => 'blog_access_id',
	'value' => $values['access_id']
));

$publish_date_label = elgg_echo('blog:publish_date');
$publish_date_input = elgg_view('input/datetime', array(
	'internalname' => 'publish_date',
	'internalid' => 'blog_publish_date',
	'value' => $values['publish_date']
));

$categories_input = elgg_view('categories', $vars);

// hidden inputs
//$container_guid_input = elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $values['container_guid']));
$guid_input = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $values['guid']));
$forward_input = elgg_view('input/hidden', array('internalname' => 'forward', 'value' => $forward));

// editing or creating.
if (isset($values['guid'])) {
	$page_title = elgg_echo('blog:edit') . ": \"{$values['title']}\"";
} else {
	$page_title = elgg_echo('blog:new');
}

// display notice if editing an old revision
if (isset($vars['revision']) && $vars['revision'] instanceof ElggAnnotation) {
	$page_title .= ' ' . elgg_echo('blog:edit_revision_notice');
}

$form_body = <<<___END
<h2>$page_title</h2>

$draft_warning

<p class="margin-top">
	<label for="blog_title">$title_label</label>
	$title_input
</p>

<p>
	<label for="blog_excerpt">$excerpt_label</label>
$excerpt_input
</p>

<label for="blog_description">$body_label</label>
$body_input
<br />

<p>
	<label for="blog_tags">$tags_label</label>
	$tags_input
</p>

<p>
	<label for="blog_comments_on">$comments_label</label>
	$comments_input
</p>

<p>
	<label for="blog_access_id">$access_label</label>
	$access_input
</p>

<p>
	<label for="blog_status">$status_label</label>
	$status_input
</p>

$categories_input

<div class="divider"></div>
<p class="margin-none margin-top entity-subtext">
	$save_status <span class="blog-save-status-time">$saved</span>
</p>

$guid_input
$container_guid_input
$forward_input

$action_buttons

___END;

echo elgg_view('input/form', array(
	'internalid' => 'blog-post-edit',
	'internalname' => 'blog_post',
	'action' => "action/blog/save",
	'body' => $form_body
));

elgg_clear_sticky_form('blog');

?>

<script type="text/javascript">
	setInterval("blogSaveDraft()", 60000);

	/*
	 * Attempt to save and update the input with the guid.
	 */
	function blogSaveDraftCallback(data, textStatus, XHR) {
		if (textStatus == 'success' && data.success == true) {
			var form = $('form[name=blog_post]');

			// update the guid input element for new posts that now have a guid
			form.find('input[name=guid]').val(data.guid);

			oldDescription = form.find('textarea[name=description]').val();

			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) {
				mins = '0' + mins;
			}
			$(".blog-save-status-time").html(d.toLocaleDateString() + " @ " + d.getHours() + ":" + mins);
		} else {
			$(".blog-save-status-time").html("<?php echo elgg_echo('error'); ?>");
		}
	}

	function blogSaveDraft() {
		if (typeof(tinyMCE) != 'undefined') {
			tinyMCE.triggerSave();
		}

		// only save on changed content
		var form = $('form[name=blog_post]');
		var description = form.find('textarea[name=description]').val();
		var title = form.find('input[name=title]').val();

		if (!(description && title) || (description == oldDescription)) {
			return false;
		}

		var draftURL = "<?php echo elgg_get_site_url(); ?>action/blog/auto_save_revision";
		var postData = form.serializeArray();

		// force draft status
		$(postData).each(function(i, e) {
			if (e.name == 'status') {
				e.value = 'draft';
			}
		});

		$.post(draftURL, postData, blogSaveDraftCallback, 'json');
	}

	$(document).ready(function() {
		// get a copy of the body to compare for auto save
		oldDescription = $('form[name=blog_post]').find('textarea[name=description]').val();

/* we don't seems to be using the text counter anymore�
		$('#excerpt.excerpt').each(function(){
			var allowed = 200;

			// set the initial value
			$('#countervalue').text(allowed);

			// bind on key up event
			$(this).keyup(function(){
				var counter_value = ((allowed - ($(this).val().length)));

				$("#countervalue").removeClass();

				if ((counter_value > 10)) {
					$("#countervalue").addClass("positive");
				}
				else if ((counter_value <= 10) && (counter_value >= 0)) {
					$("#countervalue").addClass("gettingclose");
				}
				else if ((counter_value < 0)) {
					$("#countervalue").addClass("negative");
				}

				// insert new length
				$('#countervalue').text(counter_value);

			});
		});
*/
	});

</script>