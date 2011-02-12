<?php
/**
 * Log browser search form
 *
 * @package ElggLogBrowser
 */
?>

<div id="logbrowser-search-area">
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
		
		//@todo Forms 1.8: Convert to use elgg_view_form()
		$wrappedform = elgg_view('input/form', array(
			'body' => $form,
			'method' => 'get',
			'action' => "pg/admin/overview/logbrowser/",
			'disable_security' => true,
		));
?>

	<div id="log-browser-search-form" class="elgg-module elgg-module-inline hidden">
		<div class="elgg-head">
			<h3><?php echo elgg_echo('logbrowser:search'); ?></h3>
		</div>
		<div class="elgg-body">
			<?php echo $wrappedform; ?>
		</div>
	</div>
	<p>
		<a class="link" href="#" onclick="elgg_slide_toggle(this,'#logbrowser-search-area','#log-browser-search-form');">
			<?php echo elgg_echo('logbrowser:search'); ?>
		</a>
	</p>
</div>