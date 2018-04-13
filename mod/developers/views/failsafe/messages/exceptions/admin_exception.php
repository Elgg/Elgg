<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

$exception = $vars['object'];
/* @var \Exception $exception */
?>

<p class="elgg-messages-exception">
	<span title="<?= get_class($exception); ?>">
	<?= nl2br($exception->getMessage()); ?>
		<br /><br />
		Log at time <?= $vars['ts']; ?> may have more data.
	</span>
</p>

<?php
if ($exception instanceof \DatabaseException) {
	// likely contains credentials
	return;
}
?>

<p class="elgg-messages-exception">
	<?= nl2br(htmlentities($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8')); ?>
</p>
