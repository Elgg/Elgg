<?php

namespace Elgg\File\Controllers;

use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;

/**
 * Elgg file uploader/edit action
 *
 * @since 6.2
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	/**
	 * {@inheritdoc}
	 */
	protected function sanitize(): void {
		parent::sanitize();
		
		$this->request->setParam('guid', $this->request->getParam('guid', $this->request->getParam('file_guid'))); // @todo remove in Elgg 7.0
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws ValidationException
	 */
	protected function validate(): void {
		parent::validate();
		
		if (!empty($this->request->getParam('guid'))) {
			return;
		}
		
		// upload is required for new files
		if (empty(elgg_get_uploaded_file('upload', false))) {
			throw new ValidationException(elgg_echo('file:uploadfailed'));
		}
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws InternalServerErrorException
	 */
	protected function execute(array $skip_field_names = []): void {
		parent::execute($skip_field_names);
		
		/** @var @vars \ElggFile $file */
		$file = $this->entity;
		
		if (!$file->save()) {
			throw new InternalServerErrorException();
		}
		
		$uploaded_file = elgg_get_uploaded_file('upload', false);
		if ($uploaded_file) {
			if (!$file->acceptUploadedFile($uploaded_file)) {
				throw new InternalServerErrorException(elgg_echo('file:uploadfailed'));
			}
			
			if (!$file->save()) {
				throw new InternalServerErrorException(elgg_echo('file:uploadfailed'));
			}
			
			// remove old icons
			$file->deleteIcon();
			
			// update icons
			if ($file->getSimpleType() === 'image') {
				$file->saveIconFromElggFile($file);
			}
		}
	}
}
