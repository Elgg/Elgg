define(['jquery', 'jquery-cropper/jquery-cropper'], function($) {
	
	function Cropper() {
		var $field;
		var $fieldWrapper;
		var $img;
		var $imgWrapper;
		var $inputWrapper;
		var $messagesWrapper;
		var that = this;
		
		this.init = function(selector) {
			$field = $(selector);
			$fieldWrapper = $field.closest('.elgg-field');
			$imgWrapper = $fieldWrapper.siblings('.elgg-entity-edit-icon-crop-wrapper');
			$img = $imgWrapper.find('> img').eq(0);
			$inputWrapper = $fieldWrapper.siblings('.elgg-entity-edit-icon-crop-input').eq(0);
			
			$messagesWrapper = $fieldWrapper.siblings('.elgg-entity-edit-icon-crop-messages');
			
			$field.on('change', this.replaceImg);
			
			$remove = $fieldWrapper.siblings('.elgg-entity-edit-icon-remove').find('input[type="checkbox"]');
			$remove.on('change', this.checkRemoveState);
			
			if ($img[0].hasAttribute('src')) {
				this.reload();
			}
		};
	
		this.replaceImg = function() {
			var oFReader = new FileReader();
			oFReader.readAsDataURL(this.files[0]);
			
			// remove previous state
			$imgWrapper.addClass('hidden');
			$img.off('crop.iconCropper');
			$img.cropper('destroy');
			$img.attr('src', '');
			
			that.resetMessages();
			
			// validate image
			oFReader.onload = function (oFREvent) {
				var image = new Image();
				image.src = this.result;
				
				image.onload = function(imageEvent) {
					$img.attr('src', this.src);
					
					$inputWrapper.find('input[name="_entity_edit_icon_crop_guid"], input[name="_entity_edit_icon_crop_type"], input[name="_entity_edit_icon_crop_name"]').remove();
					
					that.reload({
						data: {}
					});
				};
			};
		};
		
		this.reload = function(extra_data) {
			extra_data = extra_data || {};
			
			$imgWrapper.removeClass('hidden');
			
			var data = $img.data().iconCropper;
			$.extend(data, extra_data);
			
			$img.cropper(data);
			$img.on('crop.iconCropper', this.crop);
		};
		
		this.crop = function(event) {
			var cropDetails = $img.cropper('getData', true);
			
			that.resetMessages();
			
			var minWidth = $messagesWrapper.find('.elgg-entity-edit-icon-crop-error-width').data('minWidth');
			if (minWidth > 0 && cropDetails.width < minWidth) {
				that.showMessage('width');
			}
			var minHeight = $messagesWrapper.find('.elgg-entity-edit-icon-crop-error-height').data('minHeight');
			if (minHeight > 0 && cropDetails.height < minHeight) {
				that.showMessage('height');
			}
			
			$inputWrapper.find('input[name="x1"]').val(cropDetails.x);
			$inputWrapper.find('input[name="y1"]').val(cropDetails.y);
			$inputWrapper.find('input[name="x2"]').val(cropDetails.x + cropDetails.width);
			$inputWrapper.find('input[name="y2"]').val(cropDetails.y + cropDetails.height);
		};
		
		this.resetMessages = function() {
			if (!$messagesWrapper.length) {
				return;
			}
			
			$messagesWrapper.addClass('hidden');
			$messagesWrapper.find('.elgg-entity-edit-icon-crop-error-generic').addClass('hidden');
			$messagesWrapper.find('.elgg-entity-edit-icon-crop-error-width').addClass('hidden');
			$messagesWrapper.find('.elgg-entity-edit-icon-crop-error-height').addClass('hidden');
		};
		
		this.showMessage = function(message_type) {
			if ($.inArray(message_type, ['width', 'height']) < 0) {
				return;
			}
			
			if (!$messagesWrapper.length) {
				return;
			}
			
			$messagesWrapper.removeClass('hidden');
			$messagesWrapper.find('.elgg-entity-edit-icon-crop-error-generic').removeClass('hidden');
			$messagesWrapper.find('.elgg-entity-edit-icon-crop-error-' + message_type).removeClass('hidden');
		};
		
		this.show = function() {
			$fieldWrapper.removeClass('hidden');
			this.reload();
			$img.trigger('crop.iconCropper');
		};
		
		this.hide = function() {
			$fieldWrapper.addClass('hidden');
			$imgWrapper.addClass('hidden');
			this.resetMessages();
		};
		
		this.checkRemoveState = function() {
			if ($(this).is(':checked')) {
				that.hide();
			} else {
				that.show();
			}
		};
	};
	
	return Cropper;
});
