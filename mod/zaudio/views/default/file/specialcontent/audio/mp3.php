<?php
/**
 * ZAudio file view override
 * @package ElggZAudio
 */

echo elgg_view('zaudio/audioplayer', array('file_guid' => $vars['entity']->getGUID()));
