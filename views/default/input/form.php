<?php
/**
 * Create a form for data submission.
 * Use this view for forms rather than creating a form tag in the wild as it provides
 * extra security which help prevent CSRF attacks.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['body'] The body of the form (made up of other input/xxx views and html
 * @uses $vars['method'] Method (default POST)
 * @uses $vars['enctype'] How the form is encoded, default blank
 * @uses $vars['action'] URL of the action being called
 * @uses $vars['js'] Any Javascript to enter into the form
 * @uses $vars['internalid'] id for the form for CSS/Javascript
 * @uses $vars['internalname'] name for the form for Javascript
 * @uses $vars['disable_security'] turn off CSRF security by setting to true
 */

if (isset($vars['internalid'])) {
	$id = $vars['internalid'];
} else {
	$id = '';
}

if (isset($vars['internalname'])) {
	$name = $vars['internalname'];
} else {
	$name = '';
}
$body = $vars['body'];
$action = $vars['action'];
if (isset($vars['enctype'])) {
	$enctype = $vars['enctype'];
} else {
	$enctype = '';
}
if (isset($vars['method'])) {
	$method = $vars['method'];
} else {
	$method = 'POST';
}

$method = strtolower($method);

// Generate a security header
$security_header = "";
if (!isset($vars['disable_security']) || $vars['disable_security'] != true) {
	$security_header = elgg_view('input/securitytoken');
}
?>
<form <?php if ($id) { ?>id="<?php echo $id; ?>" <?php } ?> <?php if ($name) { ?>name="<?php echo $name; ?>" <?php } ?> <?php echo $vars['js']; ?> action="<?php echo $action; ?>" method="<?php echo $method; ?>" <?php if ($enctype!="") echo "enctype=\"$enctype\""; ?>>
<?php echo $security_header; ?>
<?php echo $body; ?>
</form>