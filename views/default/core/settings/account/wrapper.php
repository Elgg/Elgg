<?php
$title = elgg_extract('title', $vars);
$intro = elgg_extract('intro', $vars, '');
$content = elgg_extract('content', $vars);
$id = elgg_extract('id', $vars, 'settings_' . str_replace('.', '', microtime(true)));
$header = elgg_view('output/url', [
	'text' => elgg_echo('usersettings:edit', array($title)),
    'data-edit-text' => elgg_echo('usersettings:edit', array($title)),
	'rel' => 'toggle',
	'href' => "#$id",
	'class' => 'float-alt elgg-user-settings-edit',
]);
$header .= elgg_format_element('h3', ['class'=>'mbs'], $title);
$class = ['hidden elgg-river-comments elgg-border-plain pam'];
if ($intro) {
	$class[] = 'ptm';
}
$content = elgg_format_element('div', [
	'id' => $id,
	'class' => $class,
], $content);
/*
echo elgg_view_module('info', null, $intro . $content, [
	'header' => $header,
]);
*/
?>

<div class="elgg-divide-bottom">
    <div class="mam">
        <?php echo $header . $intro . $content; ?>
    </div>
</div>