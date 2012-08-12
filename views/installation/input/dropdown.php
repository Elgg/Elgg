<?php
// @deprecated Use input/select instead.

elgg_deprecated_notice('view: input/dropdown is deprecated by input/select', 1.9);

echo elgg_view('input/select', $vars);