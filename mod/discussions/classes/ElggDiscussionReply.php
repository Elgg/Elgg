<?php
/**
 * Class for discussion reply
 *
 * We extend ElggComment to get the future thread support.
 */
class ElggDiscussionReply extends ElggComment {

	const SUBTYPE = 'discussion_reply';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
	}
}
