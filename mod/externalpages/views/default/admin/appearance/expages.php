<?php
/**
 * Admin section for editing external pages
 */

$type = get_input('type', 'about');

echo elgg_view('expages/menu', array('type' => $type));

echo elgg_view_form('expages/edit', array('class' => 'elgg-form-settings'), array('type' => $type));
