<?php
	/**
	 * Elgg API Tester
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
?>
<form method="post">
	<input type="hidden" name="action" value="configure" />
	<p>API Key: <input type="text" name="apikey" value="<?php echo $vars['apikey'];?>" /></p>
	<p>Secret Key: <input type="password" name="secret" value="<?php echo $vars['secret'];?>" /></p>
	<p>Endpoint: <input type="text" name="endpoint" value="<?php echo $vars['endpoint'];?>" /></p>
	<input type="submit" name="submit" value="Set.." />
</form>