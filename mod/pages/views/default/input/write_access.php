<?php

echo elgg_view('input/access', $vars);

elgg_deprecated_notice("The input/write_access view is deprecated. The pages plugin now uses the ['access:collections:write', 'user'] hook to alter options.", "1.11");
