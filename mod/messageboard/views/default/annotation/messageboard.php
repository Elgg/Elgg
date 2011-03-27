<?php
/**
 * Message board post
 *
 * @uses $vars['annotation']  ElggAnnotation object
 * @uses $vars['full_view']        Display fill view or brief view
 */

$vars['delete_action'] = 'action/messageboard/delete';

echo elgg_view('annotation/default', $vars);