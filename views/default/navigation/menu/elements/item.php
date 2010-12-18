<?php

$item = $vars['item'];

$class = '';
if ($item->getSelected()) {
	$class = 'class="selected"';
}

echo "<li $class>{$item->getLink()}</li>";
