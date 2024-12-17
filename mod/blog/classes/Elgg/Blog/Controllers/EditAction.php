<?php

namespace Elgg\Blog\Controllers;

use Elgg\Http\OkResponse;

/**
 * Blog edit action
 *
 * Can be called by clicking save button or preview button. If preview button,
 * we automatically save as draft. The preview button is only available for
 * non-published drafts.
 *
 * Drafts are saved with the access set to private.
 *
 * @since 6.2
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	protected ?string $old_status;
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(array $skip_field_names = []): void {
		// store before changed by parent
		$revision_text = $this->entity->description;
		
		// set the previous status for the events to update the time_created and river entries
		$this->old_status = $this->entity->status;
		
		parent::execute($skip_field_names);
		
		// if this is a preview, force status to be draft
		// save or preview
		if ((bool) $this->request->getParam('preview')) {
			$this->entity->status = 'draft';
		}
		
		// if draft, set access to private and cache the future access
		if ($this->entity->status === 'draft') {
			$this->entity->future_access = $this->entity->access_id;
			$this->entity->access_id = ACCESS_PRIVATE;
		}
		
		// if this was an edit, create a revision annotation
		if (!$this->isNewEntity() && $revision_text) {
			$this->entity->annotate('blog_revision', $revision_text);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(?string $forward_url = null): OkResponse {
		$old_status = $this->old_status;
		$new_status = $this->entity->status;
		
		if (($this->isNewEntity() || $old_status === 'draft') && $new_status === 'published') {
			// add to river if changing status or published, regardless of new post
			// because we remove it for drafts.
			
			elgg_create_river_item([
				'view' => 'river/object/blog/create',
				'action_type' => 'create',
				'object_guid' => $this->entity->guid,
				'subject_guid' => $this->entity->owner_guid,
				'target_guid' => $this->entity->container_guid,
			]);
			
			elgg_trigger_event('publish', 'object', $this->entity);
			
			// reset the creation time for posts that move from draft to published
			if (!$this->isNewEntity()) {
				$this->entity->time_created = time();
				$this->entity->save();
			}
		} elseif ($old_status === 'published' && $new_status === 'draft') {
			elgg_delete_river([
				'object_guid' => $this->entity->guid,
				'action_type' => 'create',
				'limit' => false,
			]);
		}
		
		if (!isset($forward_url)) {
			if ($new_status === 'published' || (bool) $this->request->getParam('preview')) {
				$forward_url = $this->entity->getURL();
			} else {
				$forward_url = elgg_generate_url('edit:object:blog', ['guid' => $this->entity->guid]);
			}
		}
		
		return elgg_ok_response([
			'guid' => $this->entity->guid,
			'url' => $this->entity->getURL(),
		], elgg_echo('blog:message:saved'), $forward_url);
	}
}
