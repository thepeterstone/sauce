<?php

require_once dirname(dirname(__FILE__)) . '/Autoload.php';
require_once 'PHPUnit/Autoload.php';
class SvnMatcherTest extends PHPUnit_Framework_TestCase {
  private $output;

  protected function setUp() {
    $this->output = new Output();
    ob_start();
  }

  protected function tearDown() {
    // if we didn't use it, clear the buffer
    ob_clean();
  }

  public function testSubversionModificationsAreColored() {
    $this->output->stdout("M       sites");
    $this->assertRegExp('/^\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdatesAreColored() {
    $this->output->stdout("U    sites/all/themes/popcap_2012/template.php");
    $this->assertRegExp('/^\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdatesWithPlusesAreColored() {
    $this->output->stdout("M  + some/file/i/copied.php");
    $this->assertRegExp('/^\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionUpdateSummaryIsColored() {
    $this->output->stdout("Updated to revision 7.");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionLogIsColored() {
    $this->output->stdout("r3515 | pstone | 2012-02-27 12:40:29 -0800 (Mon, 27 Feb 2012) | 1 line");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testSubversionLogSeparatorIsColored() {
    $this->output->stdout("------------------------------------------------------------------------");
   $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }
}