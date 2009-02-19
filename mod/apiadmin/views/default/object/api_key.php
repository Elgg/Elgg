<?php
	global $CONFIG;
	
	$entity = $vars['entity'];
	
	$icon = elgg_view(
			'graphics/icon', array(
			'entity' => $entity,
			'size' => 'small',
		  )
		);

		
	$public_label = elgg_echo('apiadmin:public');
	$private_label = elgg_echo('apiadmin:private');
	$revoke_label = elgg_echo('apiadmin:revoke');
	
	$ts = time();
	$token = generate_action_token($ts);
		
		
	$info = "<div class=\"contentWrapper\"><p><b>{$entity->title}</b> <a href=\"{$CONFIG->url}action/apiadmin/revokekey?keyid={$entity->guid}&__elgg_token=$token&__elgg_ts=$ts\">$revoke_label</a></p></div>";
	$info .= "<div><p><b>$public_label:</b> {$entity->public}<br />"; 
	if (isadminloggedin()) {
		// Only show secret portion to admins
		
		// Fetch key
		$keypair = get_api_user($CONFIG->site_id, $entity->public);
	
		$info .= "<b>$private_label:</b> {$keypair->secret}"; 
	}
	$info .= "</p></div>";
	
	echo elgg_view_listing($icon, $info);
?>