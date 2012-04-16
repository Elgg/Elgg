<?php
/**
 * Deprecated dropdown input view - use 'input/select' instead.
 *
 * @deprecated 1.8
 */

elgg_deprecated_notice("input/dropdown was deprecated by input/select", 1.8);
echo elgg_view('input/select', $vars);