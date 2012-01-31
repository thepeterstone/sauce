<?php

require_once 'OutputClassifier.php';
require_once 'PHPUnit/Autoload.php';

class OutputClassifierTest extends PHPUnit_Framework_TestCase {

	protected $classifier;

	protected function setUp() {
		$this->classifier = new OutputClassifier();
	}

	public function testBashCommandNotFoundIsRecognized() {
		$this->assertTrue($this->classifier->check("-bash: fark: command not found"));
	}

	public function testNonsenseIsNotRecognized() {
		$this->assertFalse($this->classifier->check("wfai aiunoav ahf a fek k"));
	}

	public function testZshCommandNotFoundIsRecognized( )
	{
		$this->assertTrue($this->classifier->check("zsh: command not found: asdf"));
	}
}