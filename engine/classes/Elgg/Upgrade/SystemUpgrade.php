<?php

namespace Elgg\Upgrade;

/**
 * System upgrades are executed synchronously at system upgrade
 *
 * @see \Elgg\Application::upgrade()
 */
interface SystemUpgrade extends Batch {

}