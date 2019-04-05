define(['jquery', 'elgg'], function ($, elgg) {

	/* Autofocuses first text input in a comment form when toggled */
	$(document).on('elgg_ui_toggle', function (e, data) {
		var $toggle = $(e.target);
		var $elements = data.$toggled_elements;

		if ($elements.is('.elgg-river-responses > .elgg-form-comment-save')) {
			if ($toggle.hasClass('elgg-state-active')) {
				$elements.find('.elgg-input-text').focus();
			} else {
				$toggle.blur();
			}
		}
	});

	$(document).on('submit', '.elgg-form-comment-save', function (event) {
		var $form = $(this);

		require(['elgg/Ajax'], function(Ajax) {
			var ajax = new Ajax();

			ajax.action($form.attr('action'), {
				data: ajax.objectify($form),
				success: function(result) {
					var $container = $form.closest('.elgg-comments');
					var view_name = 'page/elements/comments';
					var comment_guid = result.guid;
					var data = {
						guid: $form.find('input[name="entity_guid"]').val(),
						id: $form.attr('id'),
						show_guid: comment_guid,
						inline: $form.find('.elgg-input-text').length
					};
						
					if (!$container.length) {
						$container = $form.closest('.elgg-river-responses');
						view_name = 'river/elements/responses';
						data.river_id = $container.closest('.elgg-river-item').parent().attr('id').replace('item-river-', '');
					}
					
					if (!$container.length) {
						return;
					}

					// the pagination returned will have a non-functional link that points to the current URL,
					// but we want the the link to reload the page.
					function fix_pagination($container) {
						function normalize(url) {
							return url.replace(/#.*/, '');
						}

						var base_url = normalize(location.href);

						$container.find('.elgg-pagination a').each(function () {
							if (normalize(this.href) === base_url) {
								$(this).on('click', function () {
									location.reload(true);
								});
							}
						});
					}
					
					ajax.view(view_name, {
						data: data,
						success: function(result) {
							if (view_name === 'river/elements/responses') {
								$container.html(result);
							} else {
								$container.html($(result).filter('.elgg-comments').html());
							}
							
							$container.find('#elgg-object-' + comment_guid).addClass('elgg-state-highlight');
							fix_pagination($container);
						}
					});
				}
			});
		});
		
		event.preventDefault();
		event.stopPropagation();
	});
	
	
	$(document).on('click', '.elgg-menu-item-edit > a', function () {
		var $trigger = $(this).closest('.elgg-menu-hover').data('trigger');
		if ((typeof $trigger === 'undefined') || !$trigger.is('.elgg-item-object-comment a')) {
			return;
		}

		// store object as data in the edit link
		var dc = $(this).data('Comment');
		if (!dc) {
			var guid = this.href.split('/').pop();
			dc = new Comment(guid, $trigger.closest('.elgg-item-object-comment'));
			$(this).data('Comment', dc);
		}
		dc.toggleEdit();
		return false;
	});
	
	function Comment(guid, item) {
		this.guid = guid;
		this.$item = item;
	}
	
	Comment.prototype = {
		/**
		 * Get a jQuery-wrapped reference to the form
		 *
		 * @returns {jQuery} note: may be empty
		 */
		getForm: function () {
			return this.$item.find('.elgg-form-comment-save');
		},

		hideForm: function () {
			this.getForm().toggleClass('hidden');
			this.getForm().prev().toggleClass('hidden');
		},

		showForm: function () {
			this.getForm().toggleClass('hidden');
			this.getForm().prev().toggleClass('hidden');
		},

		loadForm: function () {
			var that = this;

			require(['elgg/Ajax'], function(Ajax) {
				var ajax = new Ajax();
				
				// Get the form using ajax
				ajax.view('core/ajax/edit_comment?guid=' + that.guid, {
					success: function(html) {
						// Add the form to DOM
						that.$item.find('.elgg-body').first().append(html);
		
						that.showForm();
		
						var $form = that.getForm();
		
						$form.find('.elgg-button-cancel').on('click', function () {
							that.hideForm();
							return false;
						});
		
						// save
						$form.on('submit', function () {
							that.submitForm();
							return false;
						});
					}
				});
			});
		},

		submitForm: function () {
			var $form = this.getForm();

			require(['elgg/Ajax'], function(Ajax) {
				var ajax = new Ajax();
				
				ajax.action($form.attr('action'), {
					data: ajax.objectify($form),
					success: function(result) {
						if (result.output) {
							// Update list item content
							$form.closest('.elgg-item-object-comment').html(result.output);
						}
					}
				});
			});

			return false;
		},

		toggleEdit: function () {
			var $form = this.getForm();
			if ($form.length) {
				if ($form.hasClass('hidden')) {
					this.showForm();
				} else {
					this.hideForm();
				}
			} else {
				this.loadForm();
			}
			return false;
		}
	};
});
