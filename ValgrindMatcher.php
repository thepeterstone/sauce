<?php

class ValgrindMatcher extends OutputMatcher {
	protected $classifiers = array(
		'==(\d+)== ERROR SUMMARY: (\d+ errors?) from (\d+) contexts?(.*)' => array(
			'filter' => '_summary',
			'vars' => array('prefix', 'errors', 'contexts', 'extra')
		),
		'==(\d+)== Command: (\S+)(.*)' => array(
			'filter' => '_command',
			'vars' => array('prefix', 'command', 'args')
		),
		'==(\d+)==(.*)' => array(
			'filter' => '_valgrind',
			'vars' => array('prefix', 'output')
		)
	);

	protected function _summary($string, $args) {
		if ($args['errors'] !== '0 errors') {
			return $this->arg_filter($args['errors'], 'red', $string);
		}
		$string = $this->arg_filter('0 errors from 0 contexts', 'green,,bold', $string);
		return $this->arg_filter($args['prefix'], 'cyan', $string);
	}

	protected function _command($string, $args) {
		$string = $this->arg_filter($args['command'], 'yellow,,bold', $string);
		$string = $this->arg_filter($args['args'], 'yellow', $string);
		return $this->arg_filter($args['prefix'], 'cyan', $string);
	}
	protected function _valgrind($string, $args) {
		return $this->arg_filter($args['prefix'], 'cyan', $string);
	}
}