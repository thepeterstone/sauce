<?php

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
}

class SvnMatcher extends OutputMatcher {
	protected $classifiers = array(
		'([ACDGMU?])\s+([\w\/\._-]+)' => array(
			'filter' => '_status',
			'vars' => array( 'change', 'path' ),
		),
		'Updated to revision (\d+).' => array(
			'filter' => '_update',
			'vars' => array('revision id'),
		),
	);

	protected function _update($string, $args) {
		return $this->arg_replace($args['revision id'], 'green', $string);
	}

	protected function _status($string, $args) {
		$colors = array(
			'A' => 'green,,bold',
			'C' => 'red,,bold',
			'D' => 'red',
			'G' => 'green',
			'M' => 'yellow',
			'U' => 'green',
			'?' => 'blue',
		);

		$filter = $colors[$args['change']];
		return $this->arg_replace($string, $filter, $string);
	}

	private function arg_replace($search, $filter, $subject) {
		$r = str_replace($search, '%%{' . $filter . ':' . $search . '}%%', $subject);
		return $r;
	}

}

class BashMatcher extends OutputMatcher {
	protected $classifiers = array(
		'-?bash: (\w+): command not found' => array(
			'filter' => '_error',
			'vars' => array( 'missing command'),
		),
	);
}

class ZshMatcher extends OutputMatcher {
	protected $classifiers = array(
		'-?zsh: command not found: (\w+)' => array(
			'filter' => '_error',
			'vars' => array( 'missing command'),
		),
	);
}	

