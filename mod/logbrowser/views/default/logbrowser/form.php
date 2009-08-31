
<div id="logbrowser_search_area">
<?php
	
	// Time lower limit

		if ($vars['timelower']) {
			$lowerval = date('r',$vars['timelower']);
		} else {
			$lowerval = "";
		}
		if ($vars['timeupper']) {
			$upperval = date('r',$vars['timeupper']);
		} else {
			$upperval = "";
		}
		if ($vars['user_guid']) {
			if ($user = get_entity($vars['user_guid']))
				$userval = $user->username;
		} else {
			$userval = "";
		}
		

		$form = "";
		
		$form .= "<p>" . elgg_echo('logbrowser:user');
		$form .= elgg_view('input/text',array(
														'internalname' => 'search_username',
														'value' => $userval 
													)) . "</p>";
		
		$form .= "<p>" . elgg_echo('logbrowser:starttime');
		$form .= elgg_view('input/text',array(
														'internalname' => 'timelower',
														'value' => $lowerval 
													)) . "</p>";

		$form .= "<p>" . elgg_echo('logbrowser:endtime');
		$form .= elgg_view('input/text',array(
														'internalname' => 'timeupper',
														'value' => $upperval
													))  . "</p>";
		$form .= elgg_view('input/submit',array(
														'value' => elgg_echo('search')
													));
													
		$wrappedform = elgg_view('input/form',array(
														'body' => $form,
														'method' => 'get',
														'action' => $vars['url'] . "mod/logbrowser/"
										));

		if ($upperval || $lowerval || $userval) {
			$hidden = "";
		} else {
			$hidden = "style=\"display:none\"";
		}
										
?>

		<div id="logbrowserSearchform" <?php echo $hidden; ?>><?php echo $wrappedform; ?></div>
		<p>
			<a href="#" onclick="$('#logbrowserSearchform').toggle()"><?php echo elgg_echo('logbrowser:search'); ?></a>
		</p>
	</div>