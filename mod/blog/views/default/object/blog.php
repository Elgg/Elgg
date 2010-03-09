<?php
/**
 * Elgg blog individual post view
 */

$page_owner = page_owner_entity();

if (isset($vars['entity'])) {

	//display comments link?
	if ($vars['entity']->comments_on == 'Off') {
		$comments_on = false;
	} else {
		$comments_on = true;
	}	
	if (get_context() == "search" && $vars['entity'] instanceof ElggObject) {	
		//display the correct layout depending on gallery or list view
		if (get_input('search_viewtype') == "gallery") {
			//display the gallery view
       		echo elgg_view("blog/gallery",$vars);
		} else {
			echo elgg_view("blog/listing",$vars);
		}		
	} else {
		if ($vars['entity'] instanceof ElggObject) {		
			$url = $vars['entity']->getURL();
			$owner = $vars['entity']->getOwnerEntity();
			$canedit = $vars['entity']->canEdit();		
		} else {	
			$url = 'javascript:history.go(-1);';
			$owner = $vars['user'];
			$canedit = false;	
		}
				
?>

<div id="Page_Header">
	<div class="Page_Header_Title">
		<div id="content_area_user_title"><h2><?php echo $page_owner->name; ?>'s Blog</h2></div>
	</div>

	<div class="Page_Header_Options">
	<?php
		if ($vars['entity']->canEdit()) {
	?>
	<a class="Action_Button" href="<?php echo $vars['url']; ?>mod/blog/edit.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo('blog:editpost'); ?></a>
	<?php 
		echo elgg_view('output/confirmlink',array(	
				'href' => $vars['url'] . "action/blog/delete?blogpost=" . $vars['entity']->getGUID(),
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("blog:delete:confirm"),
				'class' => "Action_Button Disabled",));  
		}
	?>
	</div><div class='clearfloat'></div>
</div>

<div class="ContentWrapper singleview">
	<div class="blog_post">
			<?php
				// Allow plugins to extend
				echo elgg_view("blogs/options",array('entity' => $vars['entity']));
			?>
	<div id="content_area_user_title">
		<h2><a href="<?php echo $url; ?>"><?php echo $vars['entity']->title; ?></a></h2>
	</div>
		<!-- display the user icon -->
		<div class="blog_post_icon">
		    <?php
		        echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
			?>
	    </div>
			<p class="strapline">
			<!-- username -->
			<a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a>
			
				<?php
	                
					echo sprintf(elgg_echo("blog:strapline"),
									date("F j, Y",$vars['entity']->time_created)
					);
				
				?> 
				<!-- display the comments link -->
				<?php
					if($comments_on && $vars['entity'] instanceof ElggObject){
			        //get the number of comments
			    		$num_comments = elgg_count_comments($vars['entity']);
			    ?>
			    	<a href="<?php echo $url; ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a>
			    <?php
		    		}
		    		//sort out the access level for display
					$object_acl = get_readable_access_level($vars['entity']->access_id);
		    		//files with these access level don't need an icon
					$general_access = array('Public', 'Logged in users', 'Friends');
		    		//set the right class for access level display - need it to set on groups and shared access only
		    		$is_group = get_entity($vars['entity']->container_guid);
					if($is_group instanceof ElggGroup){
						//get the membership type open/closed
						$membership = $is_group->membership;
						if($membership == 2)
							$access_level = "class='group_open'";
						else
							$access_level = "class='group_closed'";
					}elseif($object_acl == 'Private'){
						$access_level = "class='private'";
					}else{
						if(!in_array($object_acl, $general_access))
							$access_level = "class='shared_collection'";
						else
							$access_level = "class='generic_access'";
					}
		    		echo "<br /><span {$access_level}>" . $object_acl . "</span>";
		    	?>
			</p>

			<div class="clearfloat"></div>
			<div class="blog_post_body">
			<!-- display the actual blog post and excerpt if appropriate -->
			<?php
				if($vars['entity']->show_excerpt){
					//echo "<div class='show_excerpt'>";
					//echo elgg_view('output/longtext',array('value' => $vars['entity']->excerpt));
					//echo "</div>";
				}
				echo elgg_view('output/longtext',array('value' => $vars['entity']->description));
			?>
			</div><div class="clearfloat"></div>			
			<!-- display edit options if it is the blog post owner -->
			<p class="options">
			<?php
				// Allow plugins to extend
				echo elgg_view("blogs/extend",array('entity' => $vars['entity']));
			?>
			</p>
			
			<!-- display tags -->
				<?php
	
					$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
					if (!empty($tags)) {
						echo '<p class="tags">' . $tags . '</p>';
					}
				
					$categories = elgg_view('categories/view', $vars);
					if (!empty($categories)) {
						echo '<p class="categories">' . $categories . '</p>';
					}
				
				?>
			
			<div class="clearfloat"></div>
	</div>
</div>
<?php
	}
}else{

	echo "<div class='ContentWrapper singleview'>" . elgg_echo('blog:none') . "</div>";
}