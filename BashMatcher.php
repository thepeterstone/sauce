<?php

require_once dirname(__FILE__) . '/Autoload.php';
class BashMatcher extends OutputMatcher {
	protected $classifiers = array(
		'-?bash: (\w+): command not found' => array(
			'filter' => '_error',
			'vars' => array( 'missing command'),
		),
	);
}

