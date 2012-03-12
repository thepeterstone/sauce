<?php

require_once dirname(__FILE__) . '/Autoload.php';
class SvnMatcher extends OutputMatcher {
	protected $classifiers = array(
		'([ACDGMU?])[\s+]+([\w\/\.()_-]+)' => array(
			'filter' => '_status',
			'vars' => array( 'change', 'path' ),
		),
		'(Updated to|At) revision (\d+).' => array(
			'filter' => '_update',
			'vars' => array('updated_or_at', 'revision id'),
		),
		'r(\d+) \| (\w+) \| ([^|]+) \| (\d+ lines?)' => array(
			'filter' => '_log',
			'vars' => array( 'revision id', 'user name', 'date', 'length'),
		),
		'------------------------------------------------------------------------' => array(
			'filter' => '_separator',
			'vars' => array(),
		),
	);

	protected function _update($string, $args) {
		return $this->arg_filter($args['revision id'], 'yellow,,bold', $string);
	}

	protected function _status($string, $args) {
		$colors = array(
			'A' => 'green,,bold',
			'C' => 'red,,bold',
			'D' => 'red',
			'G' => 'green',
			'M' => 'yellow',
			'U' => 'yellow',
			'?' => 'blue',
		);

		$filter = $colors[$args['change']];
		return $this->arg_filter($string, $filter, $string);
	}

	protected function _log($string, $args) {
		$string = $this->arg_filter($args['user name'], 'blue,,bold', $string);
		return $this->_update($string, $args);
	}

}
