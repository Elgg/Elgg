<?php
/**
 * Profile friends
 **/

$friends = list_entities_from_relationship('friend',$vars['entity']->getGUID(),false,'user','',0,10,false);
if(!$friends)
	$friends = "<p>This user has not made any friends yet.</p>";

?>
<div id="profile_content">
	<?php
		echo $friends;
	?>
</div>

<div id="profile_sidebar">
<?php
	echo elgg_view('profile/profile_ownerblock', array('smallicon' => true));
?>
</div>