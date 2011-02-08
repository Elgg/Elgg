<?php 
/**
 * 
 */

?>
<script type="text/javascript">
/**
 * Don't want to cache these -- they could change for every request
 */
elgg.config.lastcache = <?php echo (int)elgg_get_config('lastcache'); ?>;

elgg.security.token.__elgg_ts = <?php echo $ts = time(); ?>;
elgg.security.token.__elgg_token = '<?php echo generate_action_token($ts); ?>';

<?php
$page_owner = elgg_get_page_owner_entity();

if ($page_owner instanceof ElggEntity) {
	$page_owner_json = array();
	foreach ($page_owner->getExportableValues() as $v) {
		$page_owner_json[$v] = $page_owner->$v;
	}
	
	$page_owner_json['subtype'] = $page_owner->getSubtype();
	$page_owner_json['url'] = $page_owner->getURL();
	
	echo 'elgg.page_owner =  '.json_encode($page_owner_json).';'; 
}

$user = elgg_get_logged_in_user_entity();

if ($user instanceof ElggUser) {
	$user_json = array();
	foreach ($user->getExportableValues() as $v) {
		$user_json[$v] = $user->$v;
	}
	
	$user_json['subtype'] = $user->getSubtype();
	$user_json['url'] = $user->getURL();
	
	echo 'elgg.session.user = new elgg.ElggUser('.json_encode($user_json).');'; 
}
?>;
</script>