<?php

/**
 * Elgg bookmarks plugin form
 * 
 * @package ElggBookmarks
 */

if(isset($vars['entity'])){
	$guid = $vars['entity']->guid;
	$title = $vars['entity']->title;
	$address = $vars['entity']->address;
	$access_id = $vars['entity']->access_id;
	$tags = $vars['entity']->tags;
	$notes = $vars['entity']->description;
	$owner = get_entity($vars['entity']->container_guid);
	$url = "action/bookmarks/edit";
}else{
	//set some variables
	$guid = '';
	$title = get_input('title',"");
	$title = stripslashes($title); // strip slashes from URL encoded apostrophes
	$address = get_input('address',"");
	$notes = '';
	if ($address == "previous")
		$address = $_SERVER['HTTP_REFERER'];
	$tags = array();
	if(elgg_get_page_owner() instanceof ElggGroup){
		//if in a group, set the access level to default to the group
		$access_id = elgg_get_page_owner()->group_acl;
	}else{
		$access_id = get_default_access(get_loggedin_user());
	}
	$owner = get_loggedin_user();
	$url = "action/bookmarks/add";
}
?>
<form id="bookmark_edit_form" class="margin-top" action="<?php echo elgg_get_site_url() . $url; ?>" method="post">
	<?php echo elgg_view('input/securitytoken'); ?>
	<p>
		<label>
			<?php 	echo elgg_echo('title'); ?>
			<?php
					echo elgg_view('input/text',array(
							'internalname' => 'title',
							'value' => $title,
					)); 
			?>
		</label>
	</p>
	<p>
		<label>
			<?php 	echo elgg_echo('bookmarks:address'); ?>
			<?php
					echo elgg_view('input/url',array(
							'internalname' => 'address',
							'value' => $address,
					)); 
			?>
		</label>
	</p>
	<p>
		<label>
			<?php 	echo elgg_echo('bookmarks:addnote'); ?>
			<br />
			<?php

					echo elgg_view('input/text',array(
							'internalname' => 'notes',
							'value' => $notes,
					)); 
			
			?>
		</label>
	</p>
	<p>
		<label>
			<?php 	echo elgg_echo('tags'); ?>
			<?php
					echo elgg_view('input/tags',array(
							'internalname' => 'tags',
							'value' => $tags,
					)); 
			?>
		</label>
	</p>
	<p>
		<label>
			<?php 	echo elgg_echo('access'); ?>
			<?php
					//if it is a group, pull out the group access view
					if(elgg_get_page_owner() instanceof ElggGroup){
						$access_options = group_access_options($owner);
						echo elgg_view('input/access', array('internalname' => 'access', 
																		'value' => $access_id, 
																		'options' => $access_options));
					}else{
						echo elgg_view('input/access', array('internalname' => 'access', 
																		'value' => $access_id));
					}
			?>
		</label>
	</p>
	<p>
		<?php echo $vars['container_guid'] ? elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $vars['container_guid'])) : ""; ?>
		<input type="hidden" value="<?php echo $guid; ?>" name="guid" />
		<input type="submit" onfocus="blur()" value="<?php echo elgg_echo('save'); ?>" />
	</p>
</form>
