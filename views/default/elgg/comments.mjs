import 'jquery';

function update_menu_item_badge(result) {
	if (result.container_guid === undefined || result.container_comment_count === undefined) {
		return;
	}
	
	const $menu_items = $(document).find('[data-guid="' + result.container_guid + '"] nav li[data-menu-item="comment"]');
	$menu_items.each(function(index, elem) {
		const $badge = $(elem).find('> a > span.elgg-badge');
		if (result.container_comment_count) {
			if ($badge.length) {
				$badge.html(result.container_comment_badge_text);
			} else {
				$(elem).find('> a').append('<span class="elgg-badge">' + result.container_comment_badge_text + '</span>');
			}
		} else if ($badge.length) {
			$badge.remove();
		}
	});
}

/* Autofocuses first text input in a comment form when toggled */
$(document).on('elgg_ui_toggle', function (e, data) {
	const $toggle = $(e.target);
	const $elements = data.$toggled_elements;

	if ($elements.is('.elgg-river-responses > .elgg-form-comment-save')) {
		if ($toggle.hasClass('elgg-state-active')) {
			$elements.find('.elgg-input-text').focus();
		} else {
			$toggle.blur();
		}
	}
});

$(document).on('click', '.elgg-toggle-comment', function () {
	const $anchor = $(this);
	const comment_guid = $anchor.data().loadComment;
	
	const $placeholder = $('div[data-comments-placeholder=' + comment_guid + ']');
	if (!$placeholder.is(':empty')) {
		$placeholder.slideToggle('medium');
		return;
	}
	
	import('elgg/Ajax').then((Ajax) => {
		const ajax = new Ajax.default();
		
		ajax.form('comment/save', {
			data: {
				guid: comment_guid
			},
			success: function(result) {
				$placeholder
					.html(result)
					.slideToggle('medium')
					.find('textarea, [contenteditable]').filter(':visible').first().focus();
			}
		});
	});
});

$(document).on('submit', '.elgg-form-comment-save', function (event) {
	const $form = $(this);
	
	$form.find('.elgg-button-submit').prop('disabled', true);

	import('elgg/Ajax').then((Ajax) => {
		const ajax = new Ajax.default();

		ajax.action($form.attr('action'), {
			data: ajax.objectify($form),
			success: function(result) {
				const comment_guid = result.guid;
				let $container = $form.closest('.elgg-comments');
				let view_name = 'page/elements/comments';
				let data = {
					guid: $form.find('input[name="entity_guid"]').val(),
					id: $form.attr('id'),
					show_guid: comment_guid,
					inline: $form.find('.elgg-input-text').length
				};
				
				// update comment menu items badges
				update_menu_item_badge(result);
				
				if (!$container.length) {
					$container = $form.closest('.elgg-river-responses');
					view_name = 'river/elements/responses';
					data.river_id = $container.closest('.elgg-river-item').parent().attr('id').replace('item-river-', '');
				}
				
				if (!$container.length) {
					$form.find('.elgg-button-submit').prop('disabled', true);
					return;
				}

				// the pagination returned will have a non-functional link that points to the current URL,
				// but we want the link to reload the page.
				function fix_pagination($container) {
					function normalize(url) {
						return url.replace(/#.*/, '');
					}

					const base_url = normalize(location.href);

					$container.find('.elgg-pagination a').each(function () {
						if (normalize(this.href) === base_url) {
							$(this).on('click', function () {
								location.reload();
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
						
						const $comment = $container.find('#elgg-object-' + comment_guid);
						$comment.addClass('elgg-state-highlight');
						
						$comment[0].scrollIntoView({behavior: 'smooth'});
	
						fix_pagination($container);
					},
					error: function() {
						$form.find('.elgg-button-submit').prop('disabled', false);
					}
				});
			},
			error: function () {
				$form.find('.elgg-button-submit').prop('disabled', false);
			}
		});
	});
	
	event.preventDefault();
	event.stopPropagation();
});


$(document).on('click', '.elgg-menu-item-edit > a', function () {
	const $trigger = $(this).closest('.elgg-menu-hover').data('trigger');
	if ((typeof $trigger === 'undefined') || !$trigger.is('.elgg-item-object-comment a')) {
		return;
	}

	// store object as data in the edit link
	let dc = $(this).data('Comment');
	if (!dc) {
		const guid = $(this).data().commentGuid;
		dc = new Comment(guid, $trigger.closest('.elgg-item-object-comment'));
		$(this).data('Comment', dc);
	}
	
	dc.toggleEdit();
	
	import('elgg/popup').then((popup) => {
		popup.default.close();
	});
	
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
		return this.$item.find('#edit-comment-' + this.guid);
	},

	hideForm: function () {
		this.getForm().toggleClass('hidden');
		this.getForm().prev().toggleClass('hidden');
	},

	showForm: function () {
		this.getForm().toggleClass('hidden');
		this.getForm().prev().toggleClass('hidden');
		
		this.getForm().find('textarea, [contenteditable]').filter(':visible').first().focus();
	},

	loadForm: function () {
		const that = this;

		import('elgg/Ajax').then((Ajax) => {
			const ajax = new Ajax.default();
			
			// Get the form using ajax
			ajax.view('core/ajax/edit_comment?guid=' + that.guid, {
				success: function(html) {
					// Add the form to DOM
					that.$item.find('.elgg-body').first().append(html);
	
					that.showForm();
	
					const $form = that.getForm();
	
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
		const $form = this.getForm();
		$form.find('.elgg-button-submit').prop('disabled', true);
		
		import('elgg/Ajax').then((Ajax) => {
			const ajax = new Ajax.default();
			
			ajax.action($form.attr('action'), {
				data: ajax.objectify($form),
				success: function(result) {
					if (result.output) {
						// Update list item content
						$form.closest('.elgg-item-object-comment').html(result.output);
					}
					
					// update comment menu items badges
					update_menu_item_badge(result);
				},
				error: function() {
					$form.find('.elgg-button-submit').prop('disabled', false);
				}
			});
		});

		return false;
	},

	toggleEdit: function () {
		const $form = this.getForm();
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
