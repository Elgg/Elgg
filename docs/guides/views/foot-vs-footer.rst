Page/elements/foot vs footer
============================

``page/elements/footer`` is the content that goes inside this part of the page:

.. code-block:: html

	<div class="elgg-page-footer">
		<div class="elgg-inner">
			<!-- page/elements/footer goes here -->
		</div>
	</div>

It's content is visible to end users and usually where you would put a sitemap or other secondary global navigation, copyright info, powered by elgg, etc.

``page/elements/foot`` is inserted just before the ending ``</body>`` tag and is mostly meant as a place to insert scripts that don't already work with ``elgg_register_js(array('location' => 'footer'));`` or ``elgg_require_js('amd/module');``. In other words, you should never override this view and probably don't need to extend it either. Just use the ``elgg_*_js`` functions instead
