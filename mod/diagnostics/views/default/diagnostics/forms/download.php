<?php
/**
 * Elgg diagnostics
 * 
 * @package ElggDiagnostics
 * 
 * @deprecated 1.8
 */

elgg_deprecated_notice("Use elgg_view_form('diagnostics/download') instead of elgg_view('diagnostics/forms/download')", 1.8);

echo elgg_view_form("diagnostics/download");
