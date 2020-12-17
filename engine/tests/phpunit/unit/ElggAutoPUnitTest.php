<?php

/**
 * @group UnitTests
 */
class ElggAutoPUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggAutoP
	 */
	protected $_autop;

	public function up() {
		$this->_autop = new \ElggAutoP();
	}

	public function down() {

	}

	public function testDomRoundtrip() {
		$d = dir($this->normalizeTestFilePath('autop/'));
		$in = file_get_contents($d->path . "/domdoc_in.html");
		$exp = file_get_contents($d->path . "/domdoc_exp.html");
		$exp = $this->flattenString($exp);

		$doc = new DOMDocument();
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

	public function provider() {
		$d = dir($this->normalizeTestFilePath('autop/'));
		$tests = array();
		while (false !== ($entry = $d->read())) {
			if (preg_match('/^([a-z\\-]+)\.in\.html$/i', $entry, $m)) {
				$tests[] = $m[1];
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
	 */
	public function flattenString($string) {
		$r = preg_replace('/[\n\r]+/', '', $string);
		return $r;
	}

}
