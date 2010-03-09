<?php

/**
 * Elgg bookmarks plugin form
 * 
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

if(isset($vars['entity'])){
	$guid = $vars['entity']->guid;
	$title = $vars['entity']->title;
	$address = $vars['entity']->address;
	$access_id = $vars['entity']->access_id;
	$tags = $vars['entity']->tags;
	$notes = $vars['entity']->description;
	$url = "action/bookmarks/edit";
}else{
	//set some variables
	$guid = '';
	$title = get_input('title',"");
	$address = get_input('address',"");
	$notes = '';
	if ($address == "previous")
		$address = $_SERVER['HTTP_REFERER'];
	$tags = array();
	if(page_owner_entity() instanceof ElggGroup){
		//if in a group, set the access level to default to the group
		$access_id = page_owner_entity()->group_acl;
	}else{
		$access_id = get_default_access(get_loggedin_user());
	}
	$owner = $vars['user'];
	$url = "action/bookmarks/add";
}
?>
<form class="margin_top" action="<?php echo $vars['url'] . $url; ?>" method="post">
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
					if(page_owner_entity() instanceof ElggGroup){
						$access_options = group_access_options(page_owner_entity());
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