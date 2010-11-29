<?php
/**
 * Log browser search form
 *
 * @package ElggLogBrowser
 */
?>

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
		

		$form = "<p>" . elgg_echo('logbrowser:user');
		$form .= elgg_view('input/text', array(
			'internalname' => 'search_username',
			'value' => $userval,
		)) . "</p>";
		
		$form .= "<p>" . elgg_echo('logbrowser:starttime');
		$form .= elgg_view('input/text', array(
			'internalname' => 'timelower',
			'value' => $lowerval,
		)) . "</p>";

		$form .= "<p>" . elgg_echo('logbrowser:endtime');
		$form .= elgg_view('input/text', array(
			'internalname' => 'timeupper',
			'value' => $upperval,
		))  . "</p>";
		$form .= elgg_view('input/submit', array(
			'value' => elgg_echo('search'),
		));
		
		$wrappedform = elgg_view('input/form', array(
			'body' => $form,
			'method' => 'get',
			'action' => "pg/admin/overview/logbrowser/",
			'disable_security' => true,
		));
?>

	<div id="log_browser_search_form" class="hidden radius8"><?php echo $wrappedform; ?></div>
	<p>
		<a class="link" onclick="elgg_slide_toggle(this,'#logbrowser_search_area','#log_browser_search_form');">
			<?php echo elgg_echo('logbrowser:search'); ?>
		</a>
	</p>
</div>