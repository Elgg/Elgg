<?php
namespace Elgg\Debug\Inspector;

/**
 * Builds API display data from APIs in api.json
 *
 * @todo rewrite using https://github.com/phpDocumentor/ReflectionDocBlock Problem is we're bound to
 *       an older version (2.0.4)
 *
 * @access private
 */
class ApiReader {

	/**
	 * @var string
	 */
	private $docs_url;

	/**
	 * Get API data
	 *
	 * @return \stdClass
	 */
	public function getData() {
		// TODO 2.2 doesn't work...
		//list ($major, $minor) = explode('.', elgg_get_version(true));
		//$this->docs_url = "http://learn.elgg.org/en/$major.$minor/";

		$this->docs_url = "http://learn.elgg.org/en/latest/";

		$file = __DIR__ . '/../../../../api.json';
		$data = json_decode(file_get_contents($file));
		if (!is_array($data)) {
			throw new \RuntimeException('api.json is invalid');
		}

		$ret = (object)[];
		foreach ($data as $obj) {
			if (!empty($obj->url)) {
				$obj->url = preg_replace('~^DOCS/~', $this->docs_url, $obj->url);
			}

			foreach ($obj->items as $i => $item) {
				if (is_string($item)) {
					if (0 === strpos($item, 'function:')) {
						$item = $this->analyzeFunction(substr($item, 9));
					}
				}

				$obj->items[$i] = $item;
			}

			$ret->sections[] = $obj;
		}

		return $ret;
	}

	/**
	 * Build a function docs item
	 *
	 * @param string $name Function name
	 *
	 * @return \stdClass
	 */
	private function analyzeFunction($name) {
		$reflection = new \ReflectionFunction($name);

		// capture argument list from code without regex
		$start = $reflection->getStartLine() - 1;
		$end = $reflection->getEndLine() - 1;
		$code = file($reflection->getFileName());
		$code = array_slice($code, $start, $end - $start);
		$tokens = token_get_all("<?php " . implode("\n", $code));
		$args = null;
		foreach ($tokens as $token) {
			if ($args === null) {
				// ignore before "("
				if ($token === '(') {
					$args = '(';
				}
				continue;
			}

			if ($token === '{') {
				break;
			}

			$args .= is_string($token) ? $token : $token[1];
		}
		$args = trim($args);

		// isolate actual contents of phpdoc
		$doc = $reflection->getDocComment();
		$doc = trim($doc, "/*");
		$doc = str_replace(["\r\n", "\r"], "\n", $doc);
		$doc = preg_replace('~\s*\n\s*~', "\n", $doc);
		$doc = preg_replace('~\n\* ?~', "\n", $doc);

		if (strpos($doc, "\n\n") !== false) {
			list ($summary, $doc) = explode("\n\n", $doc, 2);
		} else {
			$summary = '(unknown)';
		}
		$doc = trim($doc);
		$summary = trim($summary);

		$doc = htmlspecialchars($doc, ENT_QUOTES);
		$doc = preg_replace('~^(@\w+)~m', '<b class="elgg-api-tag">$1</b>', $doc);

		$id = elgg_get_friendly_title($name);

		return (object)[
			'type' => 'function',
			'id' => $id,
			'name' => $name,
			'args' => $args,
			'summary' => $summary,
			'doc_html' => $doc,
		];
	}
}
