<?php

namespace Elgg\File\Controllers;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;

/**
 * Elgg file uploader/edit action
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	/**
	 * {@inheritdoc}
	 */
	public function sanitize(): void {
		parent::sanitize();
		
		$this->request->setParam('guid', $this->request->getParam('guid', $this->request->getParam('file_guid'))); // @todo remove in Elgg 7.0
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws ValidationException
	 */
	public function validateInput(): void {
		parent::validateInput();
		
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
	public function execute(array $skip_field_names = []): void {
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
