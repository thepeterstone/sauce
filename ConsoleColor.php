<?php

class ConsoleColor {
  public $bg, $fg, $attr;

  public function __construct($foreground = 'reset', $background = 'reset', $attribute = 'reset') {
    $this->set(array('bg' => $background, 'fg' => $foreground, 'attr' => $attribute));
  }

  public function set($setup) {
    if (is_array($setup)) {
      if (isset($setup['bg'])) $this->bg = self::cid($setup['bg']);
      if (isset($setup['fg'])) $this->fg = self::cid($setup['fg']);
      if (isset($setup['attr'])) $this->attr = self::aid($setup['attr']);
    } elseif (is_string($setup)) {
      if (preg_match('/(\w+)(,\w*(,\w+)?)?/', $setup, $n)) {
        if (isset($n[2])) $this->bg = self::cid($n[2]);
        if (isset($n[1])) $this->fg = self::cid($n[1]);
        if (isset($n[3])) $this->attr = self::aid($n[3]);
      }
    }
  }

  public function wrap($string) {
    $reset = new self();
    if ($this == $reset) return $string;
    return $this->__toString() . $string . $reset->__toString();
  }

  public function __toString() {
    return sprintf("%c[%d;3%d;4%dm", 033, $this->attr, $this->fg, $this->bg);
  }


  private static function cid($name) {
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

  private static function aid($name) {
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
