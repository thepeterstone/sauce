<?php

require_once dirname(__FILE__) . '/Autoload.php';
class SvnMatcher extends OutputMatcher {
	protected $classifiers = array(
		'([ACDGMU?])\s+([\w\/\._-]+)' => array(
			'filter' => '_status',
			'vars' => array( 'change', 'path' ),
		),
		'(Updated to|At) revision (\d+).' => array(
			'filter' => '_update',
			'vars' => array('updated_or_at', 'revision id'),
		),
	);

	protected function _update($string, $args) {
		return $this->arg_filter($args['revision id'], 'yellow', $string);
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
		return $this->arg_filter($string, $filter, $string);
	}

}
