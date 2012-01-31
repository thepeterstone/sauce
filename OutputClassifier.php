<?php

class OutputClassifier {
	private $classifiers = array(
		'/-bash: (\w+): command not found/',
	);


	public function check($string) {
		foreach ($this->classifiers as $test) {
			if (preg_match($test, $string)) return true;
		}
		return false;
	}
}