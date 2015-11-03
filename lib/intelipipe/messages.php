<?php


#### NORMAL MESSAGES ####


function  message ($cmd, $msg, $type, $debug = false) {


  # adjust output for if is a normal command

  if (is_null ($msg) || is_array ($msg)) {
  
  
    # set the message
  
    if (is_string ($cmd)) {
      $msg = $cmd;
      
    } elseif (is_array ($cmd)) {
    
      $msg = get_opt ($cmd, 'msg');
      
      if (!check_opt_set_type ($cmd, $msg, 'msg', 'string'))
        return  false;
    }
    
    
    # set the command
    
    if ($debug)
      $cmd = "debug_$type";
    else
      $cmd = $type;
      
  } else {
  
    # split the substring from the command
    
    $cmd = explode (':', $cmd);
    $cmd = $cmd[0];
  }


  # switch on type
    
  switch ($type) {
  
    case  'error'   :
    case  'warning' :
    case  'notice'  :
    case  'echo'    :    
      break;
    case  'none'    :
      return  true;
      
    default :
      $msg = "unknown message type '$type'";
      $type = 'error';
      
      dump_yaml (debug_backtrace);
  }
  
  
  # build output array

  $out = array ();
    
  if ($cmd && $cmd != $type && $cmd != "debug_$type")
    $out[] = "[$cmd] ";
  
  if ($type != 'echo')
    $out[] = '[' . strtoupper ($type) . '] ';
    
  $out[] = ucfirst ($msg);
  
  $out = implode ($out);
  
  
  # echo message

  echo "$out\n";
  
  
  # return false on error

  if ($type == 'error') {

    $GLOBALS['error_msg'] = trim ($out);
  
    return  false;
  }
}


function  error ($cmd, $msg) {
 
  return  message ($cmd, $msg, 'error');
}


function  warning ($cmd, $msg) {

  return  message ($cmd, $msg, 'warning');
}


function  notice ($cmd, $msg) {

  return  message ($cmd, $msg, 'notice');
}



#### DEBUG MESSAGES ####

function  debug_mode () {

  return  @$GLOBALS['debug'];
}


function  debug_message ($cmd, $msg, $type) {

  if (!debug_mode ())
    return;
    
  return  message ($cmd, $msg, $type, true);
}


function  debug_error ($cmd, $msg) {
 
  return  debug_message ($cmd, $msg, 'error');
}


function  debug_warning ($cmd, $msg) {

  return  debug_message ($cmd, $msg, 'warning');
}


function  debug_notice ($cmd, $msg) {

  return  debug_message ($cmd, $msg, 'notice');
}


function  debug_echo ($cmd, $msg) {

  return  debug_message ($cmd, $msg, 'echo');
}


function  debug_echo_txt ($txt, $indent) {

  $lines = explode ("\n", $txt);

  return  debug_dump_list ($lines, $indent);
}


function  shell_echo () {

  $args = func_get_args();
  $newline = true;
  
  if ($args[0] == '-n') {
    array_unshift ($args);
  } else {
    array_push ($args, "\n");
  }
  
  $val = implode ($args);
  
  echo $val;
  
  return  $val;
}


?>