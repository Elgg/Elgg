<?php

	/**
	 * Elgg pageshell
	 * The standard HTML page shell that everything else fits into
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */

	// Set title

		if (empty($vars['title'])) {
			$title = $vars['sitename'];
		} else if (empty($vars['sitename'])) {
			$title = $vars['title'];
		} else {
			$title = $vars['sitename'] . ": " . $vars['title'];
		}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<?php echo elgg_view('metatags'); ?>
<link rel="stylesheet" href="<?php echo $vars['url']; ?>css/css.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td id="pagetop"><a href="<?php echo $vars['url']; ?>"><img src="<?php echo $vars['wwwroot']; ?>mod/test/graphics/purplecrayon.gif" alt="Elgg" width="227" height="70" border="0" /></a></td>
  </tr>
</table>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="5" align="left" valign="top" id="mainbody"><?php echo elgg_view('menu'); ?><?php

			echo elgg_view('messages/list', array('object' => $vars['sysmessages']));
		
		?></td>
  </tr>
  <tr>
    <td colspan="5">
    	&nbsp;
    </td>
  </tr>
  <tr>
    <td width="4%" align="left" valign="top" id="mainbody">&nbsp;</td>
    <td width="65%" align="left" valign="top" id="mainbody"><?php echo $vars['body']; ?></td>
    <td width="2%" align="right" valign="top" id="mainbody">&nbsp;</td>
    <td width="25%" align="right" valign="top" id="mainbody"><?php echo $vars['sidebar']; ?></td>
    <td width="4%" align="right" valign="top" id="mainbody">&nbsp;</td>
  </tr>
  <tr>
    <td height="100" colspan="5" align="center" valign="middle">
      Copyright &copy; 2004-2005 ELGG      <br />
      <a href="<?php echo $vars['url']; ?>content/about.php">About ELGG</a> | <a href="<?php echo $vars['url']; ?>content/faq.php">FAQ</a> | <a href="<?php echo $vars['url']; ?>content/privacy.php">Privacy Policy</a> | <a href="<?php echo $vars['url']; ?>content/run_your_own.php">Run your own ELGG</a></td>
  </tr>
</table>
</body>
</html>