<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 * @deprecated 1.7
 */
elgg_log('view groups/search/finishblurb was deprecated in 1.7', 'WARNING');

if ($vars['count'] > $vars['threshold']) {

?>
<div class="contentWrapper"><a href="<?php echo $vars['url']; ?>pg/search/groups/?tag=<?php echo urlencode($vars['tag']); ?>">
	<?php
	echo elgg_echo("group:search:finishblurb");
	?></a>
</div>
<?php
}
