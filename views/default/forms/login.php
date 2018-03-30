<?php
/**
 * Elgg login form
 *
 * @package    Elgg
 * @subpackage Core
 */

echo elgg_view_field([
	'#type' => 'text',

	'name' => 'username',
	'autofocus' => true,
	'required' => true,
	'#label' => elgg_echo('loginusername'),
]);

echo elgg_view_field([
	'#type' => 'password',
	'name' => 'password',
	'required' => true,
	'#label' => elgg_echo('password'),
]);

echo elgg_view('login/extend', $vars);

if (isset($vars['returntoreferer'])) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'returntoreferer',
		'value' => 'true'
	]);
}

ob_start();
?>
	<div class="elgg-foot">
		<div class="elgg-level">
			<label class="float-alt">
				<input type="checkbox" name="persistent" value="true"/>
				<?php echo elgg_echo('user:persistent'); ?>
			</label>

			<?php
			echo elgg_view('input/submit', ['value' => elgg_echo('login')]);
			?>
		</div>
		<?php
		echo elgg_view_menu('login');
		?>
	</div>
<?php
$footer = ob_get_clean();
elgg_set_form_footer($footer);
