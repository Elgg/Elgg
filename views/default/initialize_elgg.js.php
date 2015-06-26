<?php 
/**
 * Initialize Elgg's js lib with the uncacheable data
 */

$elgg = array(
	'config' => array(
		'lastcache' => (int)elgg_get_config('lastcache'),
		'viewtype' => elgg_get_viewtype(),
		'simplecache_enabled' => (int)elgg_is_simplecache_enabled(),
	),
	'security' => array(
		'token' => array(
			'__elgg_ts' => $ts = time(),
			'__elgg_token' => generate_action_token($ts),
		),
	),
	'session' => array(
		'user' => null,
	),
);

$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof ElggEntity) {
	$elgg['page_owner'] = $page_owner->toObject();
}

$user = elgg_get_logged_in_user_entity();
if ($user instanceof ElggUser) {
	$user_object = $user->toObject();
	$user_object->admin = $user->isAdmin();
	$elgg['session']['user'] = $user_object;
}

?>

var elgg = <?php echo json_encode($elgg); ?>;
<?php
// note: elgg.session.user needs to be wrapped with elgg.ElggUser, but this class isn't
// defined yet. So this is delayed until after the classes are defined, in js/lib/session.js
