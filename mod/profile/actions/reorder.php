<?php
/**
 * Elgg profile plugin reorder fields
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

admin_gatekeeper();
$ordering = get_input('fieldorder');
//if (!empty($ordering))
$result = set_plugin_setting('user_defined_fields',$ordering,'profile');

exit;