<?php

namespace Elgg\Views;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Expr\Include_;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;

/**
 * Compiles (most) view scripts to functions
 */
class ViewCompiler {
	protected $parser;
	protected $factory;
	protected $view_functions = [];
	protected $statements = [];

	public function __construct() {
		$this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$this->factory = new BuilderFactory();
	}

	/**
	 * @return array
	 */
	public function getViewFunctions() {
		return $this->view_functions;
	}

	/**
	 * @return array
	 */
	public function getStatements() {
		return $this->statements;
	}

	public function parse($view, $file) {
		$code = file_get_contents($file);

		try {
			$stmts = $this->parser->parse($code);
		} catch (Error $e) {
			return;
		}

		// determine NS
		if ($stmts[0] instanceof Namespace_) {
			$ns = $stmts[0];
		} else {
			$ns = new Namespace_(null, $stmts);
		}

		$use_stmts = [];
		$func_stmts = [];
		foreach ($ns->stmts as $stmt) {
			if ($stmt instanceof Use_ || $stmt instanceof UseUse || $stmt instanceof GroupUse) {
				$use_stmts[] = $stmt;
				continue;
			}

			if ($stmt instanceof Function_ || $stmt instanceof Include_) {
				// bail
				return;
			}

			$func_stmts[] = $stmt;
		}

		$func_name = 'z' . md5($view);
		if ($ns->name) {
			$func_full_name = $ns->name->toString() . '\\' . $func_name;
		} else {
			$func_full_name = $func_name;
		}

		$func = $this->factory->function($func_name)
			->addParam($this->factory->param('vars'))
			->addStmts($func_stmts)
			->getNode();

		$ns->stmts = $use_stmts;
		$ns->stmts[] = $func;

		$this->statements[] = $ns;
		$this->view_functions[$view] = $func_full_name;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		echo (new PrettyPrinter\Standard)->prettyPrint($this->statements);
	}
}
