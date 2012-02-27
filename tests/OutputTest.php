<?php

require_once dirname(dirname(__FILE__)) . '/Autoload.php';
require_once 'PHPUnit/Autoload.php';
class OutputTest extends PHPUnit_Framework_TestCase {
  private $output;

  protected function setUp() {
    $this->output = new Output();
    ob_start();
  }

  protected function tearDown() {
    // if we didn't use it, clear the buffer
    ob_clean();
  }

  public function testStdoutIsPrinted() {
    $this->output->stdout("test");
    $this->assertStringStartsWith("test", ob_get_clean());
  }

  public function testStderrIsColored() {
    $this->output->stderr("error");
    $this->assertRegExp('/^\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testNewlineIsAppended() {
    $this->output->stdout("test");
    $this->assertStringEndsWith("\n", ob_get_clean());
  }

  public function testNewlinesCanBeSuppressed() {
    $this->output->suppressNewlines = TRUE;
    $this->output->stdout("test");
    $this->assertEquals("test", ob_get_clean());
  }

  public function testTrailingNewlineIsNotDoubled() {
    $this->output->stdout("test\n");
    $this->assertEquals("test\n", ob_get_clean());
  }


}
