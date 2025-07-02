<?php

/**
 * Custom topbar
 * @author Nikolai Shcherbin
 * @license GNU Affero General Public License version 3
 * @copyright (c) Nikolai Shcherbin 2025
 * @link https://wzm.me
**/

namespace wZm\CustomTopbar\Menus;

use Elgg\Menu\MenuItems;

/**
 * Menus
 */
class AdminHeader
{
    public function __invoke(\Elgg\Event $event): ?MenuItems
    {
        if (!elgg_in_context('admin')) {
            return null;
        }

        $return_value = $event->getValue();

        $return_value[] = \ElggMenuItem::factory([
            'name' => 'admin:topbar:logo',
            'text' => elgg_echo('admin:topbar:logo'),
            'href' => elgg_normalize_url('admin/topbar/logo'),
            'parent_name' => 'configure',
        ]);

        return $return_value;
    }
}
