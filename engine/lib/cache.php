<?php
/**
 * Elgg cache
 * Cache file interface for caching data.
 *
 * @package Elgg
 * @subpackage API
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.org/
 */

require_once dirname(dirname(__FILE__)).'/classes/ElggCache.php';
require_once dirname(dirname(__FILE__)).'/classes/ElggSharedMemoryCache.php';
require_once dirname(dirname(__FILE__)).'/classes/ElggStaticVariableCache.php';
require_once dirname(dirname(__FILE__)).'/classes/ElggFileCache.php';
