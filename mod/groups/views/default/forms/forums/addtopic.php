<?php
/**
 * Elgg Groups topic edit/add page
 *
 * @package ElggGroups
 *
 * @uses $vars['object'] Optionally, the topic to edit
 */

	// Set title, form destination
	$title = elgg_echo("groups:addtopic");
	$action = "groups/addtopic";
	$tags = "";
	$title = "";
	$message = "";
	$message_id = "";
	$status = "";
	
	// get the group guid
	$group_guid = (int) get_input('group_guid');

	// set up breadcrumbs
	$group = get_entity($group_guid);
	$access_id = $group->group_acl;
	$options = group_access_options($group);
	elgg_push_breadcrumb(elgg_echo('groups'), elgg_get_site_url()."pg/groups/world/");
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('item:object:groupforumtopic'), elgg_get_site_url()."pg/groups/forum/{$group_guid}/");
	elgg_push_breadcrumb(elgg_echo("groups:addtopic"));

	echo elgg_view('navigation/breadcrumbs');

	// set the title
	echo elgg_view_title(elgg_echo("groups:addtopic"));

?>
<!-- display the input form -->
<form id="group_addtopic" action="<?php echo elgg_get_site_url(); ?>action/<?php echo $action; ?>" method="post" class="margin-top">
<?php echo elgg_view('input/securitytoken'); ?>

	<p>
		<label><?php echo elgg_echo("title"); ?><br />
		<?php
			//display the topic title input
			echo elgg_view("input/text", array(
								"internalname" => "topictitle",
								"value" => $title,
												));
		?>
		</label>
	</p>

	<!-- display the tag input -->
	<p>
		<label><?php echo elgg_echo("tags"); ?><br />
		<?php

			echo elgg_view("input/tags", array(
								"internalname" => "topictags",
								"value" => $tags,
												));

		?>
		</label>
	</p>

	<!-- topic message input -->
	<p class="longtext_inputarea">
		<label><?php echo elgg_echo("groups:topicmessage"); ?></label>
		<?php

			echo elgg_view("input/longtext",array(
								"internalname" => "topicmessage",
								"value" => $message,
												));
		?>
	</p>

	<!-- set the topic status -->
	<p>
		<label><?php echo elgg_echo("groups:topicstatus"); ?><br />
		<select name="status">
			<option value="open" <?php if($status == "") echo "SELECTED";?>><?php echo elgg_echo('groups:topicopen'); ?></option>
			<option value="closed" <?php if($status == "closed") echo "SELECTED";?>><?php echo elgg_echo('groups:topicclosed'); ?></option>
		</select>
		</label>
	</p>

	<!-- access -->
	<p>
		<label>
			<?php echo elgg_echo('access'); ?><br />
			<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id, 'options' => $options)); ?>
		</label>
	</p>

	<!-- required hidden info and submit button -->
	<p>
		<input type="hidden" name="group_guid" value="<?php echo $group_guid; ?>" />
		<?php echo elgg_view('input/submit', array('value' => elgg_echo('post'))); ?>
	</p>

</form>
