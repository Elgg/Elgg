<?php

$upgrade = elgg_extract('upgrade', $vars);

$reflect = new ReflectionClass($upgrade);
$class = $reflect->getShortName();

// \Elgg\Upgrades\SimpleUpgrade takes care of updating a single item or property
$total = 1;

if ($upgrade instanceof \Elgg\Upgrades\BatchUpgrade) {
	$total = $upgrade->getTotal();
}

echo <<<HTML
	<div class="elgg-upgrade" data-total="{$total}" data-class="{$class}" style="background: white; border: 1px solid black; padding: 10px; margin: 10px 0;">
		<h3>{$upgrade->getTitle()}</h3>
		<p>{$upgrade->getDescription()}</p>
		<span class="upgrade-counter float-alt">0/{$total}</span>
		<span class="upgrade-timer">00:00:00</span>
		<div class="elgg-progressbar mbl"><span class="elgg-progressbar-counter upgrade-percent">0%</span></div>
		<div class="upgrade-messages"></div>
	</div>
HTML;
