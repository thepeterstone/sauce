<?php

class ConsoleColor {
  private $bg, $fg, $attr;

  public function __construct($foreground = 'reset', $background = 'reset', $attribute = 'reset') {
    $this->bg = self::cid($background);
    $this->fg = self::cid($foreground);
    $this->attr = self::aid($attribute);

  }

  public function __toString() {
    return sprintf("[%d;3%d;4%dm", $this->attr, $this->fg, $this->bg);
  }

  public function wrap($string) {
    $reset = new self();
    return $this->__toString() . $string . $reset->__toString();
  }

  protected static function cid($name) {
    $colors = array(
      'black' => 0,
      'red' => 1,
      'green' => 2,
      'yellow' => 3,
      'blue' => 4,
      'magenta' => 5,
      'cyan' => 6,
      'white' => 7,
      'reset' => 9,
    );
    return array_key_exists($name, $colors) ? $colors[$name] : 9;
  }

  protected static function aid($name) {
    $attr = array(
      'bold' => 1,
      'dim' => 2,
      'uline' => 3,
      'blink' => 5,
      'rev' => 7,
      'reset' => 9,
    );
    return array_key_exists($name, $attr) ? $attr[$name] : 9;
  }
}
