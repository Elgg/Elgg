<?php 
/**
 * Initialize Elgg's js lib with the uncacheable data
 */

if (0) { ?><script><?php }
?>
/**
 * Don't want to cache these -- they could change for every request
 */
elgg.config.lastcache = <?php echo (int)elgg_get_config('lastcache'); ?>;
elgg.config.viewtype = '<?php echo elgg_get_viewtype(); ?>';
elgg.config.simplecache_enabled = <?php echo (int)elgg_is_simplecache_enabled(); ?>;

elgg.security.token.__elgg_ts = <?php echo $ts = time(); ?>;
elgg.security.token.__elgg_token = '<?php echo generate_action_token($ts); ?>';

<?php
$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof ElggEntity) {
	echo 'elgg.page_owner =  ' . json_encode($page_owner->toObject()) . ';'; 
}

$user = elgg_get_logged_in_user_entity();
if ($user instanceof ElggUser) {
	$user_object = $user->toObject();
	$user_object->admin = $user->isAdmin();	
	echo 'elgg.session.user = new elgg.ElggUser(' . json_encode($user_object) . ');'; 
}
?>

//Before the DOM is ready, but elgg's js framework is fully initalized
elgg.trigger_hook('boot', 'system');
