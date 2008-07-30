/*
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
<li class="selected"><a href='#' onclick='$("#friends_picker_placeholder<?php echo $friendspicker + 1; ?>").load("<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=list&members=<?php echo $members; ?>"); return false;'>Collection members</a></li>
<li><a href="#" onclick='$("#friends_picker_placeholder<?php echo $friendspicker + 1; ?>").load("<?php echo $vars['url']; ?>friends/pickercallback.php?username=<?php echo $_SESSION['user']->username; ?>&type=picker&members=<?php echo $members; ?>&friends=<?php echo $friends; ?>"); return false;'>Edit collection</a></li>
</ul>
</div>
*/

<?php

?>

<div id="friendsPickerNavigationTabs">
<ul>
<li class="selected"><a href="#" class="collectionmembers">Collection members</a></li>

<li><a href="#" class="editmembers">Edit collection</a></li>

</ul>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$('a.collectionmembers').click(function () {
		// load collection members pane
		$('#friends_picker_placeholder<?php echo $friendspicker; ?>').load('collectionMembers.php');
		
		// remove selected state from previous tab
		$(this).parent().parent().find("#friendsPickerNavigationTabs li").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");
				
		return false;
    });

	$('a.editmembers').click(function () {
		// load friends picker pane
		$('#friends_picker_placeholder<?php echo $friendspicker; ?>').load('friendsPicker.php');

		// remove selected state from previous tab
		$(this).parent().parent().find("#friendsPickerNavigationTabs li").removeClass("selected");
		// add selected class to current tab
		$(this).parent().addClass("selected");
	
		return false;
    });


});
</script>