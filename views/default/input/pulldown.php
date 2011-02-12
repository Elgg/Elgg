<?php
/**
 * Deprecated pulldown input view - use 'input/dropdown' instead.
 *
 * @deprecated 1.8
 */

elgg_deprecated_notice("input/pulldown was deprecated by input/dropdown", 1.8);
echo elgg_view('input/dropdown', $vars);
