<?php
/**
 * Extended class to override the time_created
 *
 * @property string $status      The published status of the blog post (published, draft)
 * @property string $comments_on Whether commenting is allowed (Off, On)
 * @property string $excerpt     An excerpt of the blog post used when displaying the post
 */
class ElggBlog extends ElggObject {

	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'blog';
	}

	/**
	 * {@inheritDoc}
	 */
	public function canComment(int $user_guid = 0): bool {
		if (!parent::canComment($user_guid)) {
			return false;
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
	public function getExcerpt(int $length = 250): string {
		$excerpt = $this->excerpt ?: $this->description;
		
		return elgg_get_excerpt((string) $excerpt, $length);
	}
}
