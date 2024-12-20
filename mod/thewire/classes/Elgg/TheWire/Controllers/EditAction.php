<?php

namespace Elgg\TheWire\Controllers;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\OkResponse;

/**
 * Action for adding a wire post
 *
 * @since 6.2
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	/**
	 * {@inheritdoc}
	 */
	protected function sanitize(): void {
		parent::sanitize();
		
		$description = (string) $this->request->getParam('description');
		$description = trim(str_replace('&nbsp;', ' ', $description));
		
		// no html tags allowed so we strip (except links (a) for mention support)
		$description = elgg_strip_tags($description, '<a>');
		
		$this->request->setParam('description', $description);
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws ValidationException
	 */
	protected function validate(): void {
		parent::validate();
		
		$limit = (int) elgg_get_plugin_setting('limit', 'thewire');
		if ($limit < 1) {
			return;
		}
		
		$text_for_size = elgg_strip_tags((string) $this->request->getParam('description'));
		if (elgg_strlen($text_for_size) > $limit) {
			throw new ValidationException(elgg_echo('ValidationException:thewire:limit'));
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(array $skip_field_names = []): void {
		parent::execute($skip_field_names);
		
		// beginning of text or white space followed by hashtag
		// the hashtag must begin with # and contain at least one character not digit, space, or punctuation
		$matches = [];
		preg_match_all('/(^|[^\w])#(\w+[^\s\d[:punct:]\x{2018}-\x{201F}]+\w*)/u', $this->entity->description, $matches);
		
		if (!empty($matches[2])) {
			$this->entity->tags = $matches[2];
		}
		
		// must do this before saving so notifications pick up that this is a reply
		if ((int) $this->request->getParam('parent_guid')) {
			$this->entity->reply = true;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function executeAfter(): void {
		parent::executeAfter();
		
		$parent_guid = (int) $this->request->getParam('parent_guid');
		
		// set thread guid
		if ($parent_guid) {
			$this->entity->addRelationship($parent_guid, 'parent');
			
			// name conversation threads by guid of first post (works even if first post deleted)
			$parent_post = get_entity($parent_guid);
			$this->entity->wire_thread = $parent_post->wire_thread;
		} else {
			// first post in this thread
			$this->entity->wire_thread = $this->entity->guid;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(?string $forward_url = null): OkResponse {
		$parent = $this->entity->getParent();
		if (!isset($forward_url) && $parent instanceof \ElggWire) {
			$forward_url = elgg_generate_url('collection:object:thewire:thread', [
				'guid' => $parent->wire_thread,
			]);
		}
		
		return parent::success($forward_url);
	}
}
