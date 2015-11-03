<?php


function  escape_value ($value) {

  return  str_replace ('$', '$$', $value);
}


function  value ($value, $cmd = __FUNCTION__) {
    
  # Note: this function converts variables defined using @ in strings as:
  #
  # @name     => $GLOBALS['name']
  # @{name}   => $GLOBALS['name']
  # @@        => '@'
  #
  # The purpose is to facilitate using variables in our YAML-based system
  
  if (!is_string ($value))
    return  $value;
    
  $literal_char = '__MAWS_LITERAL_CHAR__';

  #echo "[[[$value]]]\n";
  
  $value = str_replace ('$$', $literal_char, $value);
  
  #echo "[[$value]]\n";
  
  $new_value = array();
    
  for ($i=0; $i<strlen ($value); $i++) {
  
    $c = $value[$i];
  
    if ($c != '$') {
       $new_value[] = $value[$i];
       continue;
    }
       
    $r = preg_match ('/^(\{?[a-zA-Z0-9_]*\}?).*/', substr ($value, $i+1), $matches);
  
    if (!$r) {
      return  error ($cmd, 'Invalid variable');
    }
        
    $match = $matches[1];
    
    if (substr ($match, 0, 1) == '{') {
    
      if (substr ($match, -1) != '}') {
      
        return  error ($cmd, "variable '$match' with opening { does not have a terminating }");
      }
    
      $var_name = substr ($match, 1, -1);
      
    } else if (substr ($match, -1) == '}') {
    
      $var_name = substr ($match, 0, -1);
      $i--;
      
    } else {
      $var_name = $match;
    }
    
    #echo "[$var_name]\n";

    if (!array_key_exists ($var_name, $GLOBALS))
      return  error ($cmd, "undefined global variable '$var_name'");
    
    $var_val = $GLOBALS[$var_name];
    
    $i += strlen ($match);
    
    if (is_null ($var_val))
      continue;
    
    if (is_array ($var_val)) {
    
      if (count ($new_value) > 0) {
        return  error ($cmd, "cannot concat strings and array '$var_val'");
      }
      
      return  $var_val;
    }
    
    $new_value[] = $var_val;
  }
  
  
  # compact the array and replace the $ symbols
  
  $val = str_replace ($literal_char, '$', implode ($new_value, ''));
  
  
  # check to see if it's a number
  
  if ((string) (int) $val == $val)
    $val = (int) $val;
  
  
  # TODO: check for floats? 
  
  return  $val;
}


function  get_var ($opts) {

  return  value ($opts);
}


function  return_value ($opts) {

  return  $opts;
}


function  dump_var ($opts, $pipe, $cmd = __FUNCTION__) {

  $var = get_opts_or_pipe ($opts, $pipe);

  var_dump ($var);
  
  if ($var === false) {
    warning ($cmd, "the dumped value is the boolean false, so will not be piped");
    
    return  true;
  }
  
  return  $var;
}


function  debug_dump_var ($opts, $pipe = null) {

  if (debug_mode ())
    return  dump_var ($opts, $pipe);
  
  return  get_opts_or_pipe ($opts, $pipe);
}



function  dump_list ($opts, $pipe = null, $offset = null, $cmd = __FUNCTION__) {

  $var = get_opts_or_pipe ($opts, $pipe);
  
  if (!is_array ($var))
    return  error ($cmd, "input to dump list is not an array");
    
  if (is_int ($pipe))
    $offset = $pipe;
    
  if ($pipe === true)
    $offset = $GLOBALS['list_indent'];
    
  if (is_null ($offset))
    $offset = 0;
    
  # TODO: dump list display
    
  if ($offset > 0) {
    $offset_str = str_repeat ('  ', $offset);
  }
    
  $new_offset = $offset + 1;
    
  foreach ($var as $line) {
  
    if (is_array ($line)) {
    
      dump_list ($line, null, $new_offset);
    }
    
    if ($offset) {
      echo $offset_str;
    }
    
    echo (string) $line, "\n";
  }
  
  return  $var;
}


function  debug_dump_list ($opts, $pipe = null, $offset = null, $cmd = __FUNCTION__) {

  if (debug_mode ())
    return  dump_list ($opts, $pipe, $offset, $cmd);

  return  get_opts_or_pipe ($opts, $pipe);
}


?>