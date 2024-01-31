<?php

$importmap = _elgg_services()->esm->getImportMapData();

echo elgg_format_element('script', ['type' => 'importmap'], json_encode($importmap));
