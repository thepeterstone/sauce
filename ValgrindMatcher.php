<?php

class ValgrindMatcher extends OutputMatcher {
	protected $classifiers = array(
		'==\d+== ERROR SUMMARY: (\d+ errors?) from (\d+) contexts?(.*)' => array(
			'filter' => '_summary',
			'vars' => array('errors', 'contexts', 'extra')
		),
		'==\d+==(.*)' => array(
			'filter' => '_separator',
			'vars' => array('output')
		)
	);

	protected function _summary($string, $args) {
		if ($args['errors'] !== '0 errors') {
			return $this->arg_filter($args['errors'], 'red', $string);
		}
		return $this->arg_filter($string, 'blue,,bold', $string);
	}
}