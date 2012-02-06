<?php

require_once dirname(__FILE__) . '/Autoload.php';
class ZshMatcher extends OutputMatcher {
	protected $classifiers = array(
		'-?zsh: command not found: (\w+)' => array(
			'filter' => '_error',
			'vars' => array( 'missing command'),
		),
	);
}	


