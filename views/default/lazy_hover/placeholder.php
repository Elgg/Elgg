<?php

global $LAZY_HOVER;

if (!isset($LAZY_HOVER)) {
	$LAZY_HOVER = array();
}

$user = elgg_extract("entity", $vars);
if (elgg_instanceof($user)) {
		
	$guid = $user->getGUID();
	$page_owner_guid = (int) elgg_get_page_owner_guid();
	$contexts = elgg_get_config("context");
	
	$md5 = md5($guid . "-" . $page_owner_guid . "-" . implode("-", $contexts));
	
	echo "<div rel='" . $md5 . "' class='hidden lazy-hover-placeholder'>";
	
	if (!in_array($md5, $LAZY_HOVER)) {
		echo "<form action='" . elgg_get_site_url() . "lazy_hover'>";
		echo "<input type='hidden' name='guid' value='$guid' />";
		
		if ($page_owner_guid) {
			echo "<input type='hidden' name='page_owner_guid' value='$page_owner_guid' />";
		}
		
		foreach ($contexts as $context) {
			echo "<input type='hidden' name='context[]' value='$context' />";
		}
		echo "</form>";
		
		$LAZY_HOVER[] = $md5;
	}
	
	echo "</div>";
}