<?php
/**
banner plugin settings
*/

echo '<div>';
echo elgg_echo('banner:text:label');
echo ' ';
echo elgg_view('input/longtext', array('name' => 'params[text]','value' => $vars['entity']->text,));
echo '</div>';
