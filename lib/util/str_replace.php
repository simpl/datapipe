<?php


function  add_spaces_around_slashes ($str) {

  return  str_replace ("/", " / ", $str);
}


function  remove_spaces ($str) {

  return  str_replace (" ", "", $str);
}


function  remove_newlines ($str) {

  return  str_replace ("\n", "", $str);
}


?>