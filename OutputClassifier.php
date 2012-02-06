<?php

require_once dirname(__FILE__) . '/Autoload.php';
class OutputClassifier {
	private $classifiers;
	private $input;
	private $match;

	public function __construct() {
		$files = scandir(dirname(__FILE__));
		foreach ($files as $file) {
			if (preg_match('/^(\w+Matcher).php$/', $file, $match)) {
				if ($file === 'OutputMatcher.php') {
					continue;
				}
				$this->classifiers[] = new $match[1]();
			}
		}
	}

	public function parse($string) {
		if ($this->classify($string)) {
			return $this->match;
		}
	}

	public function classify($string) {
		foreach ($this->classifiers as $classifier) {
			$this->match = $classifier->check($string);
			if ($this->match) {
				return $classifier->getType();
			}
		}
		return false;
	}


}