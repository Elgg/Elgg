<?php
/**
 * Provide a way of setting your full name.
 *
 * @package Elgg
 * @subpackage Core


 */

$user = elgg_get_page_owner();

// all hidden, but necessary for properly updating user details
echo elgg_view('input/hidden', array('internalname' => 'name', 'value' => $user->name));
echo elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $user->guid));
