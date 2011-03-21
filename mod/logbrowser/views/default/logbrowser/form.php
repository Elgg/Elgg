<?php
/**
 * Log browser search form
 *
 * @package ElggLogBrowser
 */
?>

<div id="logbrowser-search-area">
<?php
	
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
		'name' => 'search_username',
		'value' => $userval,
	)) . "</p>";
		
	$form .= "<p>" . elgg_echo('logbrowser:starttime');
	$form .= elgg_view('input/text', array(
		'name' => 'timelower',
		'value' => $lowerval,
	)) . "</p>";

	$form .= "<p>" . elgg_echo('logbrowser:endtime');
	$form .= elgg_view('input/text', array(
		'name' => 'timeupper',
		'value' => $upperval,
	))  . "</p>";
	$form .= elgg_view('input/submit', array(
		'value' => elgg_echo('search'),
	));
		
	//@todo Forms 1.8: Convert to use elgg_view_form()
	$wrappedform = elgg_view('input/form', array(
		'body' => $form,
		'method' => 'get',
		'action' => "admin/overview/logbrowser",
		'disable_security' => true,
	));

	$toggle_link = elgg_view('output/url', array(
		'href' => '#log-browser-search-form',
		'text' => elgg_echo('logbrowser:search'),
		'class' => 'elgg-toggler',
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
		<?php echo $toggle_link; ?>
	</p>
</div>