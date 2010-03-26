<?php
/**
 * Blog river view.
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$title = sprintf(elgg_echo('blog:river:create'), $url);
$string .= "<a href=\"" . $object->getURL() . "\">" . $object->title . "</a> <span class='entity_subtext'>" . friendly_time($object->publish_time) . "</span>";

echo $string;