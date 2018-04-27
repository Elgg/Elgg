<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @package    Elgg
 * @subpackage Core
 *
 * @uses       $vars['object'] An exception
 */

$exception = $vars['object'];
/* @var \Exception $exception */
?>

<div class="elgg-messages-exception">
	<div title="<?= get_class($exception); ?>">
	    <?= nl2br($exception->getMessage()); ?>
        <br/><br/>
		Log at time <?= $vars['ts']; ?> may have more data.
	</div>
</div>
<br/>
<?php
if ($exception instanceof \DatabaseException) {
	?>
    <div class="elgg-messages-exception">
        <pre><?= $exception->getQuery() ?></pre>
        <pre><?= var_export($exception->getParameters()) ?></pre>
    </div>
    <br/>
	<?php
	return;
}
?>

<div class="elgg-messages-exception">
	<?= nl2br(htmlentities($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8')); ?>
</div>
<br/>
