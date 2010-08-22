<?php
header('Content-type: text/css');
ob_start("compress");
function compress($buffer) {
  /* remove comments */
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  /* remove tabs, spaces, newlines, etc. */
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
  return $buffer;
}
//frameworks
include('../blueprint/screen.css');

/* template css files */
include('template.css');
include('mod_contactUs.css');
include('dropdown.css');
include('editor.css');

ob_end_flush();
?>
