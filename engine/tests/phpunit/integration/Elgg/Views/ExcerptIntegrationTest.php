<?php

namespace Elgg\Views;

use Elgg\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ExcerptIntegrationTest extends IntegrationTestCase {

	#[DataProvider('excerptProvider')]
	public function testElggGetExcerpt($in, $expected) {
		$this->assertEquals($expected, elgg_get_excerpt($in, 20));
	}

	public static function excerptProvider(): array {
		return [
			['a', 'a'],
			['  a  ', 'a'],
			[' &nbsp;&nbsp;a&nbsp;&nbsp;&nbsp;  ', 'a'],
			[' &nbsp;&nbsp;a&nbsp;&nbsp;b&nbsp;&nbsp;&nbsp;  ', 'a b'],
			['<b>a</b>', 'a'],
			['<b>a</b><script>b</script>', 'ab'],
			['abcdefghijklmnopqrstuvwxyz', 'abcdefghijklmnopqrst...'],
			['&nbsp;&nbsp; abcdefghijklmnopqrstuvwxyz', 'abcdefghijklmnopqrst...'],
			['abcdefghijklmnopqrstuvw xyz', 'abcdefghijklmnopqrst...'],
			['a bcdefghijklmnopqrstuvwxyz', 'a...'],
		];
	}
}
