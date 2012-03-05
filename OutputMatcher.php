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

	protected function _separator($string, $args) {
		return '%%{blue:' . $string . '}%%';
	}

	protected function _snip($string, $args) {
		return "<snip>";
	}

	public function getType() {
		return str_replace('Matcher', '', get_class($this));
	}

	protected function arg_filter($search, $filter, $subject) {
		if (is_null($search) || $search === '') { return $subject; }
		$position = strpos($subject, $search);
		$r = substr_replace($subject, '%%{' . $filter . ':' . $search . '}%%', $position, strlen($search));
		return $r;
	}

}
