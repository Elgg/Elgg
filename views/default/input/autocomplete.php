<?php
/**
 * Displays an autocomplete text input.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @TODO: This currently only works for ONE AUTOCOMPLETE TEXT FIELD on a page.
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['match_on'] Array | str What to match on. all|array(group|user|friend|subtype)
 * @uses $vars['match_owner'] Bool.  Match only entities that are owned by logged in user.
 *
 */

global $autocomplete_js_loaded;

$internalname = $vars['internalname'];
$value = $vars['value'];

if(!$value) {
	$value= '';
}

if($vars['internal_id']) {
	$id_autocomplete = $vars['internal_id'];
}

$ac_url_params = http_build_query(array(
	'match_on' => $vars['match_on'],
	'match_owner' => $vars['match_owner'],
));
$ac_url = $vars['url'] . 'pg/autocomplete?' . $ac_url_params;

if (!isset($autocomplete_js_loaded)) {
	$autocomplete_js_loaded = false;
}

?>

<!-- show the input -->
<input type="text" class='autocomplete' name ='<?php echo $internalname; ?>_autocomplete' value='<?php echo $value?>' />
<input type="hidden" name="<?php echo $internalname; ?>" value='<?php echo $value; ?>' />

<?php
if (!$autocomplete_js_loaded) {
	?>

	<!-- include autocomplete -->
	<script language="javascript" type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery.autocomplete.min.js"></script>
	<script type="text/javascript">
	function bindAutocomplete() {
	$('input[type=text].autocomplete').autocomplete("<?php echo $ac_url; ?>", {
		minChars: 1,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max, term) {
			eval("var info = " + row + ";");
			var r = '';

			switch (info.type) {
				case 'user':
				case 'group':
					r = info.icon + info.name + ' - ' + info.desc;
					break;

				default:
					r = info.name + ' - ' + info.desc;
					break;
			}
			return r.replace(new RegExp("(" + term + ")", "gi"), "<b>$1</b>");
		}
	});

	$('input[type=text].autocomplete').result(function(event, data, formatted) {
		eval("var info = " + data + ";");
		$(this).val(info.name);

		var hidden = $(this).next();
		hidden.val(info.guid);
	});
	}

	$(document).ready(function() {
	bindAutocomplete();
	});

	</script>

	<?php

	$autocomplete_js_loaded = true;
} else {
	?>
	<!-- rebind autocomplete -->
	<script type="text/javascript">bindAutocomplete();</script>
	<?php
}