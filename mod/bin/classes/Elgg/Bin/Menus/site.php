<?php

namespace Elgg\Bin\Menus;

/**
 * Event callbacks for menus
 *
 * @since 5.1
 */
class Site {

    /**
     * Register item to menu
     *
     * @param \Elgg\Event $event 'register', 'menu:site'
     *
     * @return \Elgg\Menu\MenuItems
     */
    public static function register(\Elgg\Event $event) {
        $return = $event->getValue();

        $return[] = \ElggMenuItem::factory([
            'name' => 'bin',
            'icon' => 'edit-regular',
            'text' => elgg_echo('item:object:bin'),
            'href' => elgg_generate_url('default:bin'),
        ]);

        return $return;
    }
}