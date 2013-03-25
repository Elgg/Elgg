<?php
/**
 * Deprecated pulldown input view - use 'input/select' instead.
 *
 * @deprecated 1.8
 */

elgg_deprecated_notice("input/pulldown was deprecated by input/select", 1.8, 2);
echo elgg_view('input/select', $vars);
