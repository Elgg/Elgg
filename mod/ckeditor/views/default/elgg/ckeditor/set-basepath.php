<?php
// note: previous attempts at setting this via an AMD module sometimes failed to
// set it before the CKeditor library was loaded. This resulted in CKeditor pulling
// resources from the wrong paths.

$path = elgg_get_simplecache_url('ckeditor') . "/";

?>
window.CKEDITOR_BASEPATH = <?= json_encode($path) ?>;
