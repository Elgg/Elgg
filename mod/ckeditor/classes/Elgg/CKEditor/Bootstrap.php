<?php

namespace Elgg\CKEditor;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		elgg_define_js('ckeditor/ckeditor', [
			'exports' => 'CKEDITOR',
		]);
		elgg_define_js('jquery.ckeditor', [
			'deps' => ['jquery', 'ckeditor/ckeditor'],
			'exports' => 'jQuery.fn.ckeditor',
		]);
	}
}
