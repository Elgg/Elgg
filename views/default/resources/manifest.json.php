<?php

elgg_set_http_header('Content-Type: application/json;charset=utf-8');

$site = elgg_get_site_entity();
$resource = new \Elgg\Http\WebAppManifestResource($site);

echo json_encode($resource->get());
