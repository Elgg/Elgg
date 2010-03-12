<?php
/**
 * Edit blog form
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$values = array(
	'title' => NULL,
	'description' => NULL,
	'status' => 'final',
	//'publish_date' => NULL,
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => NULL,
	'tags' => NULL,
	'container_guid' => NULL,
	'guid' => NULL
);

$forward = $_SERVER['HTTP_REFERER'];

$action_buttons = '';
$guid_input = '';

// if entity is set, we're editing.
if (isset ($vars['entity'])) {
	$blog = $vars['entity'];

	if ($blog && ($blog instanceof ElggObject) && ($blog->getSubtype() == 'blog')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $blog->$field;
		}
	} else {
		echo elgg_echo('blog:error:post_not_found');
		return FALSE;
	}

	// add a delete button if editing
	$delete_url = "{$vars['url']}action/blog/delete?guid={$blog->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'action_button'
	));

	$action_buttons = $delete_link;

	$guid_input = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $values['guid']));
}

$save_button = elgg_view('input/submit', array('value' => elgg_echo('save'), 'class' => 'action_button'));
$action_buttons .= $save_button;

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
	'value' => $values['excerpt']
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
	'internalname' => 'description',
	'internalid' => 'blog_description',
	'value' => $values['description']
));

$save_status = elgg_echo('blog:save_status');
$never = elgg_echo('never');

$status_label = elgg_echo('blog:status');
$status_input = elgg_view('input/pulldown', array(
	'internalname' => 'status',
	'internalid' => 'blog_status',
	'value' => $values['status'],
	'options_values' => array(
		'draft' => elgg_echo('blog:draft'),
		'final' => elgg_echo('blog:final')
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
$publish_date_input = elgg_view('input/datepicker', array(
	'internalname' => 'publish_date',
	'internalid' => 'blog_publish_date',
	'value' => $vars['publish_date']
));

// hidden inputs
//$container_guid_input = elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $values['container_guid']));
$forward_input = elgg_view('input/hidden', array('internalname' => 'forward', 'value' => $forward));

$form_body = <<<___END
<p>
	<label for="blog_title">$title_label</label>
	$title_input
</p>

<p>
	<label for="blog_excerpt">$excerpt_label</label>
$excerpt_input
</p>

<p>
	<label for="blog_description">$body_label</label>
	$body_input
</p>

<p id="blog_save_status">
	$save_status:<span id="blog_save_status_time">$never</span>
</p>

<p>
	<label for="blog_publish_date">$publish_date_label</label>
	$publish_date_input
</p>


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

$guid_input
$container_guid_input
$forward_input

$action_buttons

<span>

___END;

echo elgg_view('input/form', array(
	'internalname' => 'blog_post',
	'action' => "{$vars['url']}action/blog/save",
	'body' => $form_body
));

?>

<script type="text/javascript">
	setInterval("blogSaveDraft()", 60000);

	/*
	 * Attempt to save and update the input with the guid.
	 */
	function blogSaveDraftCallback(data, textStatus, XHR) {
		if (textStatus == 'success' && data.success == true) {
			var form = $('form[name=blog_post]');
			form.append('<input type="hidden" name="guid" value="' + data.guid + '" />');

			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) mins = '0' + mins;
			$("#blog_save_status_time").html(d.getHours() + ":" + mins);

		} else {
			$("#blog_save_status_time").html("<?php echo elgg_echo('error'); ?>");
		}
	}

	function blogSaveDraft() {
		if (typeof(tinyMCE) != 'undefined') {
			tinyMCE.triggerSave();
		}

		// only save when content exists
		var form = $('form[name=blog_post]');
		var title = form.children('input[name=title]').val();
		var description = form.children('textarea[name=description]').val();

		if (!(title && description)) {
			return false;
		}

		var draftURL = "<?php echo $vars['url']; ?>action/blog/save?ajax=1";
		var postData = form.serializeArray();

		// force draft status
		$(postData).each(function(i, e) {
			if (e.name == 'status') {
				e.value = 'draft';
			}
		});

		$.post(draftURL, postData, blogSaveDraftCallback, 'json');

	}

	$(document).ready(function(){
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
	});

</script>