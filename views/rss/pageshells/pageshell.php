<?php

	/**
	 * Elgg RSS output pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	header("Content-Type: text/xml");
	
	echo "<?xml version='1.0'?>\n";

	
	
	// Set title
		if (empty($vars['title'])) {
			$title = $vars['config']->sitename;
		} else if (empty($vars['config']->sitename)) {
			$title = $vars['title'];
		} else {
			$title = $vars['config']->sitename . ": " . $vars['title'];
		}
		
	// Remove RSS from URL
		$url = str_replace('?view=rss','',full_url());
		$url = str_replace('&view=rss','',full_url());

?>

<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
	<channel xml:base=''>
		<title><![CDATA[<?php echo $title; ?>]]></title>
		<link><?php echo htmlentities($url); ?></link>
		<?php

			echo $vars['body'];
		
		?>
	</channel>
</rss>