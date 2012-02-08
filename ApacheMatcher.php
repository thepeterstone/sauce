<?php

require_once dirname(__FILE__) . '/Autoload.php';
class ApacheMatcher extends OutputMatcher {
	protected $classifiers = array(
		'(\d+\.\d+\.\d+\.\d+) - - \[([^\]]+)\] "([^"]+)" (\d+) (-|\d+) "([^"]+)" "([^"]*)"' => array(
			'filter' => '_combined',
			'vars' => array( 'remote ip', 'date', 'request', 'status code', 'size', 'referrer', 'user agent' ),
		),
		'\[([^\]]+)\] [(\w+)] [client (\d+\.\d+\.\d+\.\d+)] (.*)' => array(
			'filter' => '_error',
			'vars' => array( 'date', 'severity', 'remote ip', 'error string'),
		),
	
	);

	protected function _error($string, $args) {
		$string = $this->arg_filter($args['severity'], $this->_errorTypeColor($args['severity']), $string);
		return $this->arg_filter($args['error string'], 'red', $string);
	}

	private function _errorTypeColor($code) {
		switch ($code) {
			case 'error':
				return 'red,,bold';
			case 'warning':
				return 'yellow,,bold';
			case 'notice':
				return 'yellow';
			default:
				return 'blue';
		}
	}

	protected function _combined($string, $args) {
		$string = $this->arg_filter($args['status code'], $this->_statusCodeColor($args['status code']), $string);
		$string = $this->arg_filter($args['request'], 'blue', $string);
		return $string;
	}

	private function _statusCodeColor($code) {
		switch (substr($code, 0, 1)) {
			case '2':
			return 'green';
			case '4':
			return 'red';
			case '5':
			return 'red,,bold';
			default:
			return 'yellow';
		}
	}
}