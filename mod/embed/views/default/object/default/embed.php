<?php

if ($vars['entity'] instanceof ElggObject) {
	$title = htmlspecialchars($vars['entity']->title, ENT_QUOTES);
	echo "<a href=\"{$vars['entity']->getURL()}\">$title</a>";
}
