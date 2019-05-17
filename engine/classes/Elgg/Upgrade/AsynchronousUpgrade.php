<?php

namespace Elgg\Upgrade;

/**
 * Interface to be implement for asynchronous upgrades, i.e. upgrades that can be executed at any time by the system admin
 */
interface AsynchronousUpgrade extends Batch {

}
