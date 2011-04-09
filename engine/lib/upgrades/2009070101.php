<?php

global $CONFIG;

/**
 * Kses appears to be a dead project so we are deprecating it in favour of htmlawed.
 */
disable_plugin('kses', $CONFIG->site->guid);
enable_plugin('htmlawed', $CONFIG->site->guid);
