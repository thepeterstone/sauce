<?php

require_once dirname(__FILE__) . '/Autoload.php';
abstract class OutputMatcher {
	protected $classifiers;

	public function check($string) {
		foreach ($this->classifiers as $test => $match) {
			if (preg_match("/^$test$/", $string, $m)) {
				$args = array();
				foreach($match['vars'] as $idx => $val) {
					$args[$val] = $m[$idx + 1];
				}
				return call_user_method($match['filter'], $this, $string, $args);
			}
		}
		return false;
	}

	protected function _error($string, $args) {
		return '%%{red,,bold:' . $string . '}%%';
	}

	public function getType() {
		return str_replace('Matcher', '', get_class($this));
	}

	protected function arg_filter($search, $filter, $subject) {
		$r = str_replace($search, '%%{' . $filter . ':' . $search . '}%%', $subject);
		return $r;
	}

}
