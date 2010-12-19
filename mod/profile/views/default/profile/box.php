<?php
/**
 * Profile info box
 */

?>
<div class="profile">
<?php
	echo elgg_view('profile/sidebar');
	echo elgg_view('profile/details', array('entity' => elgg_get_page_owner()));
?>
</div>