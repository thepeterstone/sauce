<?php

require_once dirname(__FILE__) . '/OutputMatcher.php';
class OutputClassifier {
	private $classifiers;
	private $input;
	private $match;

	public function __construct() {
		$this->classifiers = array(
			new BashMatcher(),
			new SvnMatcher(),
			new ZshMatcher(),
		);	
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

	public function parse($string) {
		if ($this->classify($string)) {
			return $this->match;
		}
	}
}