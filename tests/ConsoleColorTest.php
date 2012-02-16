<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(dirname(__FILE__)) . '/ConsoleColor.php';
class ConsoleColorTest extends PHPUnit_Framework_TestCase {
  protected $fixture, $reset;

  protected function setUp() {
    $this->fixture = new ConsoleColor('green');
    $this->reset = new ConsoleColor('reset');
  }

  public function testWrapPrependsColor() {
    $output = $this->fixture->wrap('test');
    $this->assertStringStartsWith($this->fixture->__toString(), $output);
  }

  public function testWrapAppendsReset() {
    $output = $this->fixture->wrap('test');
    $this->assertStringEndsWith($this->reset->__toString(), $output);
  }

  public function testEvalAsStringRendersColorCode() {
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', $this->fixture->__toString());
  }

  public function testUnknownColorReturnsReset() {
    $bad = new ConsoleColor('asdf');
    $this->assertEquals($this->reset, $bad);
  }
}
