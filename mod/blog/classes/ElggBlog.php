<?php
/**
 * Extended class to override the time_created
 *
 * @property string $status          The published status of the blog post (published, draft)
 * @property string $previous_status The status before blog was saved (used by event system to detect changes in the status)
 * @property string $comments_on     Whether commenting is allowed (Off, On)
 * @property string $excerpt         An excerpt of the blog post used when displaying the post
 */
class ElggBlog extends ElggObject {

	use Elgg\TimeUsing;

	const UNSAVED_DRAFT = 'unsaved_draft';
	const DRAFT = 'draft';
	const PUBLISHED = 'published';

	/**
	 * Set subtype to blog.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "blog";
	}

	/**
	 * Can a user comment on this blog?
	 *
	 * @see ElggObject::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0, $default = null) {
		$result = parent::canComment($user_guid, $default);
		if ($result == false) {
			return $result;
		}

		if ($this->comments_on == 'Off') {
			return false;
		}
		
		return true;
	}

	/**
	 * Get the excerpt for this blog post
	 *
	 * @param int $length Length of the excerpt (optional)
	 * @return string
	 * @since 1.9.0
	 */
	public function getExcerpt($length = 250) {
		if ($this->excerpt) {
			return $this->excerpt;
		} else {
			return elgg_get_excerpt($this->description, $length);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {

		if (!isset($this->status)) {
			$this->status = self::DRAFT;
		}

		if (!isset($this->previous_status)) {
			$this->previous_status = self::DRAFT;
		}

		if (!isset($this->comments_on)) {
			$this->comments_on = 'On';
		}

		if ($this->status !== $this->previous_status) {
			$this->time_created = $this->getCurrentTime()->getTimestamp();
		}

		return parent::save();
	}
}
