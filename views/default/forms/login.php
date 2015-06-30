<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div>
	<label><?php echo elgg_echo('loginusername'); ?>
		<?php
			echo elgg_view('input/text', array(
				'name' => 'username',
				'autofocus' => true,
				'required' => true
			));
		?>
	</label>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?>
		<?php
			echo elgg_view('input/password', array(
				'name' => 'password',
				'required' => true
			));
		?>
	</label>
</div>

<?php echo elgg_view('login/extend', $vars); ?>

<div class="elgg-foot">
	<label class="mtm float-alt">
		<input type="checkbox" name="persistent" value="true" />
		<?php echo elgg_echo('user:persistent'); ?>
	</label>
	
	<?php
	echo elgg_view('input/submit', array('value' => elgg_echo('login')));
	
	if (isset($vars['returntoreferer'])) {
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}

	echo elgg_view_menu('login', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-general elgg-menu-hz mtm',
	));
	?>
</div>
