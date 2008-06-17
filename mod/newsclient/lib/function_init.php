<?php
global $CFG;
// Initialise magpie

if (isset($CFG->rsspostsmaxage) && $CFG->rsspostsmaxage > 0) {
    $CFG->rsspostsmaxage = (int) $CFG->rsspostsmaxage;
} elseif (!isset($CFG->rsspostsmaxage)) {
    $CFG->rsspostsmaxage = 60;
} else {
    $CFG->rsspostsmaxage = 0;
}

if (empty($CFG->mintimebetweenrssupdate)) {
    $CFG->mintimebetweenrssupdate = 1800;
}

define('rss','true');
define('MAGPIE_DIR', $CFG->dirroot . "mod/newsclient/lib/");
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
define('MAGPIE_USER_AGENT', "Elgg's furrepticiouf feed fetcher");
require_once(MAGPIE_DIR . 'rss_fetch.inc');

?>