<?php

require_once 'OutputClassifier.php';
require_once 'PHPUnit/Autoload.php';

class OutputClassifierTest extends PHPUnit_Framework_TestCase {
	public function testBashCommandNotFoundIsRecognized( )
	{
		$oc = new OutputClassifier();
		$this->assertTrue($oc->check("-bash: fark: command not found"));
	}
}