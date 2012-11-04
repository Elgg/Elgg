<?php
/**
 * Test case for ElggAutop functionality.
 * @author Steve Clay <steve@mrclay.org>
 */
class ElggCoreOutputAutoPTest extends ElggCoreUnitTest {

	/**
	 * @var ElggAutop
	 */
	protected $_autop;

	public function setUp() {
		$this->_autop = new ElggAutop();
	}
	
	public function testDomRoundtrip()
	{
		$d = dir(dirname(__DIR__) . '/test_files/output/autop');
		$in = file_get_contents($d->path . "/domdoc_in.html");
		$exp = file_get_contents($d->path . "/domdoc_exp.html");

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML("<html><meta http-equiv='content-type' content='text/html; charset=utf-8'><body>"
				. $in . '</body></html>');
		$serialized = $doc->saveHTML();
		list(,$out) = explode('<body>', $serialized, 2);
		list($out) = explode('</body>', $out, 2);

		$this->assertEqual($exp, $out, "DOMDocument's parsing/serialization roundtrip");
	}

	public function testProcess()
	{
		$data = $this->provider();
		foreach ($data as $row) {
			list($test, $in, $exp) = $row;
			$out = $this->_autop->process($in);
			$this->assertEqual($exp, $out, "Equality case {$test}");
		}
	}

	public function provider()
	{
		$d = dir(dirname(__DIR__) . '/test_files/output/autop');
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
}
