<?php
/**
 * Friend widget display view
 *
 */

// owner of the widget
$owner = $vars['entity']->getOwnerEntity();

// the number of friends to display
$num = (int) $vars['entity']->num_display;

// get the correct size
$size = $vars['entity']->icon_size;

$html = $owner->listFriends('', $num, array(
	'size' => $size,
	'gallery' => true,
));
if ($html) {
	echo $html;
} else {

}
