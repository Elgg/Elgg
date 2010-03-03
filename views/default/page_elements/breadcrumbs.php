<?php

/**
 * Breadcrumbs
**/
// @todo spk to Brett re. making this simpler / scalable
// grab variables from array
$breadcrumb_root_url = $vars['breadcrumb_root_url'];
$breadcrumb_root_text = $vars['breadcrumb_root_text'];
$breadcrumb_level1_url = $vars['breadcrumb_level1_url'];
$breadcrumb_level1_text = $vars['breadcrumb_level1_text'];
$breadcrumb_level2_url = $vars['breadcrumb_level2_url'];
$breadcrumb_level2_text = $vars['breadcrumb_level2_text'];
$breadcrumb_currentpage = $vars['breadcrumb_currentpage']; 
?>
<div id="breadcrumbs">
	<a href="<?php echo $breadcrumb_root_url; ?>"><?php echo $breadcrumb_root_text; ?></a> &gt;
	<?php
		if (isset($vars['breadcrumb_level1_url']) && $vars['breadcrumb_level1_url']) { ?>
		<a href="<?php echo $breadcrumb_level1_url; ?>"><?php echo $breadcrumb_level1_text; ?></a> &gt;
	<?php } 
		if (isset($vars['breadcrumb_level2_url']) && $vars['breadcrumb_level2_url']) { ?>	
		<a href="<?php echo $breadcrumb_level2_url; ?>"><?php echo $breadcrumb_level2_text; ?></a> &gt; 
	<?php }
	echo $breadcrumb_currentpage; ?>
</div>