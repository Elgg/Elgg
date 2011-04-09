<?php
global $CONFIG;

/**
 * Disable update client since this has now been removed.
 */
disable_plugin('updateclient', $CONFIG->site->guid);
