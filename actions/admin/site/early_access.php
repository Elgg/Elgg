<?php

foreach (_elgg_get_known_features() as $feature) {
	set_config("feat:$feature", 'on' === get_input("feat_" . md5($feature)));
}

elgg_flush_caches();

system_message(elgg_echo("admin:configuration:success"));

forward(REFERER);
