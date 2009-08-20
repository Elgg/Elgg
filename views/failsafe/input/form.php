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
	 * 
	 */
	
	if (isset($vars['internalid'])) { $id = $vars['internalid']; } else { $id = ''; }
	if (isset($vars['internalname'])) { $name = $vars['internalname']; } else { $name = ''; }
	$body = $vars['body'];
	$action = $vars['action'];
	if (isset($vars['enctype'])) { $enctype = $vars['enctype']; } else { $enctype = ''; }
	if (isset($vars['method'])) { $method = $vars['method']; } else { $method = 'POST'; }

?>
<form <?php if ($id) { ?>id="<?php echo $id; ?>" <?php } ?> <?php if ($name) { ?>name="<?php echo $name; ?>" <?php } ?> action="<?php echo $action; ?>" method="<?php echo $method; ?>" <?php if ($enctype!="") echo "enctype=\"$enctype\""; ?>>
<?php echo $body; ?>
</form>