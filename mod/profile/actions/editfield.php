<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

admin_gatekeeper();

$field = get_input('field');
$text = get_input('value');

set_plugin_setting("admin_defined_profile_{$field}",$text,'profile');

echo $text;

exit;