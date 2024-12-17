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
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultFields(): array {
		$result = parent::getDefaultFields();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
			'required' => true,
			'id' => 'blog_title',
		];

		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('blog:excerpt'),
			'name' => 'excerpt',
			'id' => 'blog_excerpt',
		];

		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('blog:body'),
			'name' => 'description',
			'required' => true,
			'id' => 'blog_description',
		];

		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('tags'),
			'name' => 'tags',
			'id' => 'blog_tags',
		];
		
		$result[] = [
			'#type' => 'checkbox',
			'#label' => elgg_echo('comments'),
			'name' => 'comments_on',
			'id' => 'blog_comments_on',
			'default' => 'Off',
			'value' => 'On',
			'switch' => true,
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access'),
			'name' => 'access_id',
			'id' => 'blog_access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'blog',
		];
		
		$result[] = [
			'#type' => 'select',
			'#label' => elgg_echo('status'),
			'name' => 'status',
			'id' => 'blog_status',
			'options_values' => [
				'draft' => elgg_echo('status:draft'),
				'published' => elgg_echo('status:published'),
			],
		];
		
		return $result;
	}
}
