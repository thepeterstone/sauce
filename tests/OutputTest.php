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
    // @ - ob_clean spawns a warning if the buffer is empty, ignore it
    @ob_clean();
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

  public function testSubversionUpdatesWithPlusesAreColored() {
    $this->output->stdout("M  + some/file/i/copied.php");
    $this->assertRegExp('/^\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdateSummaryIsColored() {
    $this->output->stdout("Updated to revision 7.");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionAdditionWithParenIsColored() {
    $this->output->stdout('A    labs/branches/LABS_11.05.16_RC_01_pp_01/htdocs/facebook/agency/scenes/scene_bar/sc_bar_billiard_ball_(2).png');
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }
}
