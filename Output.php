#!/usr/bin/php
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
    $string = trim($string);
    $recognized = $this->classifier->parse($string);
    return $this->format($recognized ?: $string);
  }

  private function say($string) {
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

// If run directly, colorize STDIN
if (fileinode(__FILE__)===getmyinode()) {
  $out = new Output();
  $fd = fopen("php://stdin", "r");
  while ($line = fgets($fd)) {
    $out->stdout($line);
  }
}
