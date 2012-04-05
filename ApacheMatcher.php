<?php

require_once dirname(__FILE__) . '/Autoload.php';
class ApacheMatcher extends OutputMatcher {
	private $date = "\[([^\]]+)\]";
	private $ip = "(\d+\.\d+\.\d+\.\d+)";
	private $client = "(?:\[client (\d+\.\d+\.\d+\.\d+)\] |(?:))";
	private $severity = "\[(\w+)\]";
	protected $classifiers = array();

	public function __construct() {
		$this->classifiers = array(
			// access logs
			"$this->ip - - $this->date" . ' "([^"]+)" (\d+) (-|\d+) "([^"]+)" "([^"]*)"' => array(
				'filter' => '_combined',
				'vars' => array( 'remote ip', 'date', 'request', 'status code', 'size', 'referrer', 'user agent' ),
			),

			// error logs
			"$this->date $this->severity $this->client" . "File does not exist: (.*)\/favicon.ico" => array(
				'filter' => '_snip',
				'vars' => array(),
			),
			"$this->date $this->severity $this->client" . "(.*)" => array(
				'filter' => '_error',
				'vars' => array( 'date', 'severity', 'remote ip', 'error string'),
			),
		);
	}

	protected function _error($string, $args) {
		$string = $this->arg_filter($args['error string'], $this->_errorTypeColor($args['severity']), $string);
		$string = $this->arg_filter($args['severity'], 'blue,,bold', $string);
		$string = $this->arg_filter($args['remote ip'], 'cyan', $string);
		return $string;
	}

	private function _errorTypeColor($code) {
		switch ($code) {
			case 'error':
				return 'red,,bold';
			case 'warning':
				return 'yellow,,bold';
			case 'notice':
				return 'blue';
			default:
				return 'magenta';
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
