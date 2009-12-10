<?php
/**
 * Elgg friends collections
 * Lists a user's friends collections
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['collections'] The array of friends collections
 */

$friendspicker = $vars['friendspicker'];

$collectionid = $vars['collection']->id;
$ownerid = $vars['owner']->getGUID();

?>

<div id="elgg_horizontal_tabbed_nav">
<ul>
<li class="selected"><a href="#" class="collectionmembers<?php echo $friendspicker; ?>"><?php echo elgg_echo('friends:collections:members'); ?></a></li>

<li><a href="#" class="editmembers<?php echo $friendspicker; ?>"><?php echo elgg_echo('friends:collections:edit'); ?></a></li>
 	
</ul>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$('a.collectionmembers<?php echo $friendspicker; ?>').click(function () {
		// load collection members pane
		$('#friends_picker_placeholder<?php echo $friendspicker; ?>').load('<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=list&collection=<?php echo $collectionid; ?>&friendspicker=<?php echo $friendspicker; ?>');

		// remove selected state from previous tab
		$(this).parent().parent().find("li.selected").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");

		return false;
	});

	$('a.editmembers<?php echo $friendspicker; ?>').click(function () {
		// load friends picker pane
		$('#friends_picker_placeholder<?php echo $friendspicker; ?>').load('<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=picker&collection=<?php echo $collectionid; ?>&friendspicker=<?php echo $friendspicker; ?>');

		// remove selected state from previous tab
		$(this).parent().parent().find("li.selected").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");

		return false;
	});


});
</script>
