<?php

/**
 * Because Elgg now has a plugable account activation process we need to activate
 * the email account activation plugin for existing installs.
 */
enable_plugin('uservalidationbyemail', $CONFIG->site->guid);
