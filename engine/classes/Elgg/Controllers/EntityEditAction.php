<?php

namespace Elgg\Controllers;

use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Generic entity edit action controller
 *
 * @since 6.2
 */
class EntityEditAction extends GenericAction {
	
	protected \ElggEntity $entity;
	
	protected bool $is_new_entity = true;
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws InternalServerErrorException
	 * @throws EntityPermissionsException
	 */
	protected function executeBefore(): void {
		parent::executeBefore();
		
		$type = $this->request->getHttpRequest()->getRoute()?->getOption('entity_type');
		$subtype = $this->request->getHttpRequest()->getRoute()?->getOption('entity_subtype');
		if (!isset($type, $subtype)) {
			throw new InternalServerErrorException(elgg_echo('actionnotfound', [(string) $this->request->getHttpRequest()->getRoute()?->getName()]));
		}
		
		$entity_class = elgg_get_entity_class($type, $subtype);
		if (empty($entity_class)) {
			throw new InternalServerErrorException();
		}
		
		$guid = (int) $this->request->getParam('guid');
		if (!empty($guid)) {
			$entity = get_entity($guid);
			if (!$entity instanceof $entity_class) {
				throw new InternalServerErrorException();
			}
			
			if (!$entity->canEdit()) {
				throw new EntityPermissionsException();
			}
			
			$this->is_new_entity = false;
		} else {
			/* @var \ElggEntity $entity */
			$entity = new $entity_class();
			
			$container_guid = (int) $this->request->getParam('container_guid', elgg_get_logged_in_user_guid());
			$container = get_entity($container_guid);
			if (!$container || !$container->canWriteToContainer(0, $entity->getType(), $entity->getSubtype())) {
				throw new EntityPermissionsException();
			}
			
			$entity->container_guid = $container->guid;
		}
		
		$this->entity = $entity;
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @param array $skip_field_names field names to skip when saving field metadata
	 *
	 * @throws ValidationException
	 */
	protected function execute(array $skip_field_names = []): void {
		parent::execute();
		
		foreach ($this->entity->getFields() as $field) {
			$name = (string) elgg_extract('name', $field);
			if (in_array($name, $skip_field_names)) {
				continue;
			}
			
			$field_type = (string) elgg_extract('#type', $field);
			$value = $this->request->getParam($name);
			
			if ($field_type === 'switch') {
				$value = (bool) $value;
			} elseif ($field_type === 'tags') {
				$value = elgg_string_to_array((string) $value);
			} elseif ($name === 'title') {
				$value = elgg_get_title_input();
			}
			
			if ($field_type === 'file') {
				$uploaded_file = elgg_get_uploaded_file($name, false);
				if ($uploaded_file && !$uploaded_file->isValid()) {
					throw new ValidationException(elgg_get_friendly_upload_error($uploaded_file->getError()));
				}
			}
			
			if ($field_type === 'url' && !elgg_is_empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
				throw new ValidationException(elgg_echo('ValidationException:field:url', [$name]));
			}
			
			if (elgg_extract('required', $field) && elgg_is_empty($value)) {
				throw new ValidationException(elgg_echo('ValidationException:field:required', [$name]));
			}
			
			if ($field_type === 'file') {
				// files need their own save logic for now
				continue;
			}
			
			$this->entity->{$name} = $value;
		}
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws InternalServerErrorException
	 */
	protected function executeAfter(): void {
		parent::executeAfter();
		
		if (!$this->entity->save()) {
			throw new InternalServerErrorException(elgg_echo('save:fail'));
		}
		
		if ($this->request->getParam('header_remove')) {
			$this->entity->deleteIcon('header');
		} else {
			$this->entity->saveIconFromUploadedFile('header', 'header');
		}
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @param string|null $forward_url url to forward to
	 */
	protected function success(?string $forward_url = null): OkResponse {
		$this->createRiverItem();
		
		$success_keys = [
			"entity:edit:{$this->entity->getType()}:{$this->entity->getSubtype()}:success",
			"entity:edit:{$this->entity->getType()}:success",
		];
		
		$message = elgg_echo('entity:edit:success');
		foreach ($success_keys as $success_key) {
			if (elgg_language_key_exists($success_key)) {
				$message = elgg_echo($success_key);
				break;
			}
		}
		
		if (!isset($forward_url)) {
			$forward_url = get_input('forward_url');
		}
		
		return elgg_ok_response('', $message, $forward_url ?: $this->entity->getURL());
	}
	
	/**
	 * Is the entity being saved a new entity or being updated
	 *
	 * @return bool
	 */
	protected function isNewEntity(): bool {
		return $this->is_new_entity;
	}
	
	/**
	 * On successful action create a river time
	 *
	 * By default, this will only happen when a new entity was created
	 *
	 * @return void
	 * @since 7.0
	 */
	protected function createRiverItem(): void {
		//add to river only if new
		if (!$this->isNewEntity()) {
			return;
		}
		
		if (!$this->entity->hasCapability('river_emittable')) {
			return;
		}
		
		elgg_create_river_item([
			'action_type' => 'create',
			'object_guid' => $this->entity->guid,
			'target_guid' => $this->entity->container_guid,
		]);
	}
}
