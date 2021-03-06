<?php

require_once dirname(__FILE__) . '/Autoload.php';
class Output {
  public $suppressNewlines = FALSE;

  protected $classifier;

  public function __construct() {
    $this->classifier = new OutputClassifier();
  }

  public function stdout($arg) {
    $this->say($this->filter($arg));
  }

  public function stderr($arg) {
    $filter = new ConsoleColor('red', NULL, 'bold');
    $this->say($filter->wrap($this->filter($arg)));
  }

  protected function filter($string) {
    $string = trim($string, "\r\n");
    $recognized = $this->classifier->parse($string);
    if ($recognized !== FALSE) {
      $string = $recognized;
    }
    return $this->format($string);
  }

  private function say($string) {
    if (empty($string)) {
      return;
    }
    print $string . ($this->suppressNewlines ? '' : "\n");
  }

  private function format($string) {
    $colorizer = new ConsoleColor();

    $token = '/%%\{([\w,]+):(.+?)\}%%/';
    if (preg_match($token, $string, $m)) {
      list($match, $filter, $text) = $m;
      $colorizer->set($filter);
      return $this->format(str_replace($match, $colorizer->wrap($text), $string));
    }
    return $string;
  }


}
