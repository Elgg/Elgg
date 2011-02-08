<?php
global $CONFIG;

/**
 * Disable members plugin as it has been moved into core.
 */
disable_plugin('members', $CONFIG->site->guid);
