<?php
/**
 * Top page annotation river view.
 *
 * @package ElggPages
 */

$statement = $vars['statement'];
$performed_by = $statement->getSubject();
$object = $statement->getObject();

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = elgg_echo("pages:river:posted", array($url)) . " ";
$string .= elgg_echo("pages:river:annotate:create") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";

echo $string;
