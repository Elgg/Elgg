<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 */
	 
?>

<div id="content_area_group_title"><h2><?php echo elgg_echo("groups:forum"); ?></h2></div>

<?php
    //only show the add link if the user is a member
    if(page_owner_entity()->isMember($vars['user'])){
     
?>
        <!-- display the add a topic link -->
        <div class="add_topic"><a href="<?php echo $vars['url']; ?>pg/forum/new/<?php echo $vars['group_guid']; ?>" class="add_topic_button"><?php echo elgg_echo("groups:addtopic"); ?></a></div>

<?php
    }
?>    
<?php
	if($vars['topics'])
		echo $vars['topics'];
	else
		echo "<div class='contentWrapper'>". elgg_echo("grouptopic:notcreated") . "</div>";

?>