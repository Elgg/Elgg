<?php
/**
 * Deprecated content wrapper view from Elgg 1.5 through 1.7
 *
 * @uses $vars['body'] The content to display inside content wrapper
 * @uses $vars['subclass'] Additional css class
 */

elgg_deprecated_notice("The 'page_elements/contentwrapper' has been deprecated", 1.8);
?>

<div class="contentWrapper<?php

	if (isset($vars['subclass'])) {
		echo ' ' . $vars['subclass'];
	}

?>">
<?php
	echo $vars['body'];
?>
</div>
