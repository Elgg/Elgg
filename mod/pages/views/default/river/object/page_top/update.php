<?php
/**
 * Top page update river view
 *
 * @package ElggPages
 */

$statement = $vars['statement'];
$performed_by = $statement->getSubject();
$object = $statement->getObject();

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = elgg_echo("pages:river:updated", array($url)) . " ";
$string .= elgg_echo("pages:river:update") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";

echo $string;