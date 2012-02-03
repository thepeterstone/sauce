<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(dirname(__FILE__)) . '/Output.php';
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
    $this->assertRegExp('/^\[\d;3\d;4\dm/', ob_get_clean());
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

  public function testSubversionModificationsAreColored() {
    $this->output->stdout("M       sites");
    $this->assertRegExp('/^\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdatesAreColored() {
    $this->output->stdout("U    sites/all/themes/popcap_2012/template.php");
    $this->assertRegExp('/^\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdateSummaryIsColored() {
    $this->output->stdout("Updated to revision 7.");
    $this->assertRegExp('/\[\d;3\d;4\dm/', ob_get_clean());
  }
}
