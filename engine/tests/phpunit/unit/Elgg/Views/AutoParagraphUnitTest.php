<?php

namespace Elgg\Views;

class AutoParagraphUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var AutoParagraph
	 */
	protected $_autop;

	public function up() {
		$this->_autop = new AutoParagraph();
	}

	public function testDomRoundtrip() {
		$d = dir($this->normalizeTestFilePath('autop/'));
		$in = file_get_contents($d->path . "/domdoc_in.html");
		$exp = file_get_contents($d->path . "/domdoc_exp.html");
		$exp = $this->flattenString($exp);

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML("<html><meta http-equiv='content-type' content='text/html; charset=utf-8'><body>"
				. $in . '</body></html>', LIBXML_NOBLANKS);
		$serialized = $doc->saveHTML();
		list(, $out) = explode('<body>', $serialized, 2);
		list($out) = explode('</body>', $out, 2);
		$out = $this->flattenString($out);

		$this->assertEquals($exp, $out, "DOMDocument's parsing/serialization roundtrip");
	}

	/**
	 * @dataProvider provider
	 */
	public function testProcess($test, $in, $exp) {
		$exp = $this->flattenString($exp);
		$out = $this->_autop->process($in);
		$out = $this->flattenString($out);

		$this->assertEquals($exp, $out, "Equality case {$test}");
	}

	public static function provider() {
		$d = dir(self::normalizeTestFilePath('autop/'));
		$tests = array();
		while (false !== ($entry = $d->read())) {
			$matches = [];
			if (preg_match('/^([a-z\\-]+)\.in\.html$/i', $entry, $matches)) {
				$tests[] = $matches[1];
			}
		}
		
		$data = array();
		foreach ($tests as $test) {
			$data[] = array(
				$test,
				file_get_contents($d->path . '/' . "{$test}.in.html"),
				file_get_contents($d->path . '/' . "{$test}.exp.html"),
			);
		}
		return $data;
	}

	/**
	 * Different versions of PHP return different whitespace between tags.
	 * Removing all line breaks normalizes that.
	 *
	 * @return string
	 */
	protected function flattenString($string) {
		return preg_replace('/[\n\r]+/', '', $string);
	}
}
