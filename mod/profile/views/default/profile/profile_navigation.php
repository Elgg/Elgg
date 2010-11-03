<?php

$section = 'activity';
if (isset($vars['section'])) {
	$section = $vars['section'];
}

$user = $vars['entity'];
if (!$user) {
	// no user so no profile
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}


$activity = '';
$friends = '';
$extend = '';
$twitter = '';

$url = "{$user->getURL()}/";

//select section
switch($section){
	case 'friends':
		$friends = 'class="selected"';
		break;

	case 'details':
		$details = 'class="selected"';
		break;
	case 'groups':
		$groups = 'class="selected"';
		break;
	case 'twitter':
		$twitter = 'class="selected"';
		break;

	case 'commentwall':
		$commentwall = 'class="selected"';
		break;
	case 'activity':
	default:
		$activity = 'class="selected"';
		break;
}
?>
<div class="elgg_horizontal_tabbed_nav profile">
<div class="profile_name"><h2><?php echo $user->name; ?></h2></div>
<ul>
	<li <?php echo $activity; ?>><a href="<?php echo $url; ?>"><?php echo elgg_echo('activity'); ?></a></li>
	<li <?php echo $details; ?>><a href="<?php echo $url . 'details'; ?>"><?php echo elgg_echo('Details'); ?></a></li>
	<li <?php echo $friends; ?>><a href="<?php echo $url . 'friends'; ?>"><?php echo elgg_echo('friends'); ?></a></li>
	<li <?php echo $groups; ?>><a href="<?php echo $url . 'groups'; ?>"><?php echo elgg_echo('groups'); ?></a></li>
	<li <?php echo $commentwall; ?>><a href="<?php echo $url . 'commentwall'; ?>"><?php echo elgg_echo('profile:commentwall'); ?></a></li>
	<?php
		//check to see if the twitter username is set
		if($vars['entity']->twitter){
	?>
			<li <?php echo $twitter; ?>><a href="<?php echo $url . 'twitter'; ?>">Twitter</a></li>
	<?php
		}

		//insert a view which others can extend
		echo elgg_view('profile_navigation/extend', array('entity' => $user));
	?>
</ul>
</div>