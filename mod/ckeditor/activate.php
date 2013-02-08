<?php
/**
 * Register the subtype on activation
 */

if (get_subtype_id('object', 'ckeditor_upload')) {
	update_subtype('object', 'ckeditor_upload', 'CKEditorUpload');
} else {
	add_subtype('object', 'ckeditor_upload', 'CKEditorUpload');
}
