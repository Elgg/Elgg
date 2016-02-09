<?php

// this is just to be detected by the pages edit form for deprecation purposes.
echo "<!-- -->";

echo elgg_view('input/access', $vars);

if (!elgg_extract('purpose', $vars)) {
	// a dev has extended the page edit form
	elgg_deprecated_notice("The input/write_access view is deprecated. The pages plugin now uses the ['access:collections:write', 'user'] hook to alter options.", "1.11");
}
