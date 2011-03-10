<?php
/**
 * @package Elgg
 * @subpackage Core
 * @deprecated 1.7
 */

elgg_deprecated_notice('view user/search/finishblurb was deprecated.', 1.7);

if ($vars['count'] > $vars['threshold']) {

?>
<a href="<?php echo elgg_get_site_url(); ?>search/users?tag=<?php echo urlencode($vars['tag']); ?>"><?php
	echo elgg_echo("user:search:finishblurb");
	?></a>
<?php

}
