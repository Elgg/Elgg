<?php

/**
 * Custom topbar
 * @author Nikolai Shcherbin
 * @license GNU Affero General Public License version 3
 * @copyright (c) Nikolai Shcherbin 2025
 * @link https://wzm.me
**/

namespace wZm\CustomTopbar\Menus;

class Topbar
{
    public function __invoke(\Elgg\Event $event): ?\Elgg\Menu\MenuItems
    {
        if (elgg_in_context('admin')) {
            return null;
        }

        $user = elgg_get_logged_in_user_entity();
        if (!$user instanceof \ElggUser) {
            return null;
        }

        $return = $event->getValue();

        $return[] = \ElggMenuItem::factory([
            'name' => 'account',
            'text' => false,
            'href' => false,
            'link_class' => 'elgg-avatar-small',
            'icon' => elgg_view('output/img', [
                'src' => $user->getIconURL('small'),
                'alt' => $user->getDisplayName(),
            ]),
            'priority' => 800,
            'section' => 'alt',
            'child_menu' => [
                'display' => 'dropdown',
                'class' => 'elgg-menu-hover',
                'data-position' => json_encode([
                    'at' => 'right bottom',
                    'my' => 'right top+8px',
                    'collision' => 'fit fit',
                ]),
                'id' => 'account-menu',
            ],
        ]);

        return $return;
    }
}
