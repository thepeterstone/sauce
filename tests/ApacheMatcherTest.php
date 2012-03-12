<?php

require_once dirname(dirname(__FILE__)) . '/Autoload.php';
require_once 'PHPUnit/Autoload.php';
class ApacheMatcherTest extends PHPUnit_Framework_TestCase {
  private $output;

  protected function setUp() {
    $this->output = new Output();
    ob_start();
  }

  protected function tearDown() {
    // if we didn't use it, clear the buffer
    ob_clean();
  }

  public function testApacheShutdownNoticeIsColored() {
    $this->output->stdout("[Mon Feb 06 15:42:32 2012] [notice] SIGHUP received.  Attempting to restart");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testApachePhpErrorIsColored() {
    $this->output->stdout("[Mon Feb 06 15:35:20 2012] [error] [client 10.1.2.29] PHP Parse error:  syntax error, unexpected '[' in /settings.php on line 49");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testApacheWarningIsColored() {
    $this->output->stdout("[Mon Feb 06 15:35:20 2012] [warning] Something isn't right...");
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testCombinedLogFormatSuccessIsRecognized() {
    $this->output->stdout('64.62.178.162 - - [06/Feb/2012:14:58:21 -0800] "GET /jmx-console/ HTTP/1.1" 200 1545 "-" "-"');
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }

  public function testCombinedLogFormatFailureIsRecognized() {
    $this->output->stdout('64.62.178.162 - - [06/Feb/2012:14:58:21 -0800] "GET /jmx-console/ HTTP/1.1" 400 509 "-" "-"');
    $this->assertRegExp('/\e\[\d;3\d;4\dm/', ob_get_clean());
  }
}