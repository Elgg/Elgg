<?php
/**
 * Elgg 1.8-svn upgrade 2011031800
 * widgets_arent_plugins
 *
 * At some point in Elgg's history subtype widget was registered with class ElggPlugin.
 * Fix that.
 */

update_subtype('object', 'widget', 'ElggWidget');
