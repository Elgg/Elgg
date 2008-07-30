<?php

	global $friendspicker;
	if (!isset($friendspicker)) $friendspicker = 0;
	
	if (isset($vars['members'])) {
		$members = implode(',',$vars['members']);
	} else {
		$members = "";
	}
	
	$friends = "";
	if (isset($vars['friends'])) {
		foreach($friends as $friend) {
			if (!empty($friends)) $friends .= ",";
			$friends .= $friend->getGUID();
		}
	}
	
?>

<div id="friendsPickerNavigationTabs">
<ul>
<li class="selected"><a href="#" class="collectionmembers<?php echo $friendspicker + 1; ?>">Collection members</a></li>

<li><a href="#" class="editmembers<?php echo $friendspicker + 1; ?>">Edit collection</a></li>

</ul>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$('a.collectionmembers<?php echo $friendspicker + 1; ?>').click(function () {
		// load collection members pane
		$('#friends_picker_placeholder<?php echo $friendspicker + 1; ?>').load('<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=list&members=<?php echo $members; ?>');
		
		// remove selected state from previous tab
		$(this).parent().parent().find("li.selected").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");
				
		return false;
    });

	$('a.editmembers<?php echo $friendspicker + 1; ?>').click(function () {
		// load friends picker pane
		$('#friends_picker_placeholder<?php echo $friendspicker + 1; ?>').load('<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=picker&members=<?php echo $members; ?>&friends=<?php echo $friends; ?>');

		// remove selected state from previous tab
		$(this).parent().parent().find("li.selected").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");
	
		return false;
    });


});
</script>