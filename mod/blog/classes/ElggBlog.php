<?php
/**
 * Extended class to override the time_created
 *
 * @property string $status      The published status of the blog post (published, draft)
 * @property string $comments_on Whether commenting is allowed (Off, On)
 * @property string $excerpt     An excerpt of the blog post used when displaying the post
 * @property string $new_post    Whether this is an auto-save (not fully saved) ("1" = yes, "" = no)
 */
class ElggBlog extends ElggObject {

	/**
	 * {@inheritDoc}
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

		if ($this->comments_on === 'Off' || $this->status !== 'published') {
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
		$excerpt = $this->excerpt ?: $this->description;
		
		return elgg_get_excerpt($excerpt, $length);
	}

}
