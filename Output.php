<?php

include_once('ConsoleColor.php');
class Output {
  public $suppressNewlines = FALSE;

  public function stdout($arg) {
    $this->say($this->colorize($arg));
  }

  public function stderr($arg) {
    $filter = new ConsoleColor('red', NULL, 'bold');
    $this->say($filter->wrap($arg));
  }

  protected function colorize($string) {
    $string = trim($string);
    if (preg_match('/^([ADGMU?])       /', $string, $m)) {
      $svn_colors = array('A' => 'green', 'D' => 'red', 'G' => 'green', 'M' => 'yellow', 'U' => 'green', '?' => 'blue');
      $filter = new ConsoleColor($svn_colors[$m[1]]);
      return $filter->wrap($string);
    } 
    return $string;
  }

  protected function say($string) {
    print $string . ($this->suppressNewlines ? '' : "\n");
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
