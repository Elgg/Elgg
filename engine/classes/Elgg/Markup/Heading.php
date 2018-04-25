<?php

namespace Elgg\Markup;

/**
 * <h1>, <h2>, <h3> etc elements
 *
 * @method static h1(mixed $children = null, array $attributes = [])
 * @method static h2(mixed $children = null, array $attributes = [])
 * @method static h3(mixed $children = null, array $attributes = [])
 * @method static h4(mixed $children = null, array $attributes = [])
 * @method static h5(mixed $children = null, array $attributes = [])
 * @method static h6(mixed $children = null, array $attributes = [])
 */
class Heading extends Tag {

	protected $tag_name = 'h1';

}