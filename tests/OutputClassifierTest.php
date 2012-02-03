<?php

require_once 'OutputClassifier.php';
require_once 'PHPUnit/Autoload.php';

class OutputClassifierTest extends PHPUnit_Framework_TestCase {

	protected $classifier;

	protected function setUp() {
		$this->classifier = new OutputClassifier();
	}

	public function testBashCommandNotFoundIsRecognized() {
		$this->assertEquals('Bash', $this->classifier->classify("-bash: fark: command not found"));
	}

	public function testNonsenseIsNotRecognized() {
		$this->assertFalse($this->classifier->classify("wfai aiunoav ahf a fek k"));
	}

	public function testZshCommandNotFoundIsRecognized( )
	{
		$this->assertEquals('Zsh', $this->classifier->classify("zsh: command not found: asdf"));
	}
}