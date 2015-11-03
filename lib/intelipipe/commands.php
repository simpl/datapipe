<?php


$process_commands_no_set_value = <<<'END'

process_data:
  #mawk: true

END;


function  process_commands ($cmds) {


  # load the commands as YAML if not an array

  if (is_string ($cmds)) {
    $cmds = yaml_decode ($cmds);
  }

  
  # get the values for which they are not converted
  
  $no_set_values = yaml_decode ($GLOBALS["process_commands_no_set_value"]);
  
  
  # get the existing pipe data
  
  $pipe = @$GLOBALS['pipe_data'];
  
  
  # loop through the commands
  
  for ($i=0; $i<count($cmds); $i++) {
  
    $cmd = $cmds[$i];
  
    if (is_string ($cmd))
      $cmd = array ($cmd => null);
  
    foreach ($cmd as $func => $opts) {
    
    
      # check to see if we are setting a variable
      
      if (substr ($func, 0, 1) == '$') {
      
      
        # set the variable as the pipe
      
        if ($opts == null) {
        
          $pipe = value ($func);
        
        } else {
      
          $var_name = substr ($func, 1);
            
          $GLOBALS[$var_name] = $opts;
        }
        continue;
      }
      
    
      # special case if the function is 'echo'
    
      if ($func == 'echo') {
      
        echo value ($opts), "\n";
        continue;
      }
    
    
      # check that the function exists
      
      if (!function_exists ($func))
        return  error ($func, "function '$func' does not exist");
      
    
      # check to see if the input is a string that can be converted to an array
      
      if (is_string ($opts)) {
                
        $opts = value ($opts);
      }
      
    
      # re-write variables passed to the array
    
      if (is_array ($opts) && count ($opts) > 0) {
      
        foreach ($opts as $opt => $value) {
        
          if ($opt == '=' || !is_string ($value))
            continue;
          
          if (!@$no_set_values[$func][$opt]) {

            $opts[$opt] = value ($value);
          }
          
          if (@$GLOBALS['error'] || @$GLOBALS['error_msg'])
            return  false;
        }
      }
      
    
      # call the function passing the options

      $r = $func ($opts, $pipe);

      if ($r === false)
        return  false;
      
      
      # check to see if we should set a value upon return
      
      if (is_array ($opts)) {
      
        if (array_key_exists ('=', $opts)) {
        
          $var = $opts['='];
          
          if (!is_string ($var)) {
            return  error ($func, "var name to save data to is not a string");
          }
          
          if (substr ($var, 0, 1) != '$') {
            return  error ($func, "var name '$var' for saving does not start with a '$'");
          }
        
          debug_echo ($func, "saving response to var '$var'");
        
          $var_name = substr ($var, 1);
        
          $GLOBALS[$var_name] = $r;
        }
      }
      
      
      # define the $pipe for the next piped command
      
      $pipe = $r;
    }
  }

  $GLOBALS['pipe_data'] = $pipe;
  
  return  ($r === false ? false : true);
}


function  process_commands_list ($commands_list, $plugin_name = '') {


  # check inputs

  if (!is_string ($plugin_name))
    return  error (null, "plugin name is not a string");
  
  if (!is_array ($commands_list))
    return  error (null, "commands to process are not an array");

  if (empty ($commands_list))
    return  notice (null, "no commands to process");
    

  # loop through all the commands

  foreach ($commands_list as $commands) {
  
    $r = process_commands ($commands);
    
    if (@$GLOBALS['error'] === false)
      $r = true;
    
    if ($r === true) {
    
      $syslog = get_config_value ('syslog_on_success', false);
    
      if ($syslog) {
      
        $syslog_error_msg = "[SureDone $plugin_name plugin] [Success]";
        $syslog_priority = get_config_value ('syslog_success_priority', LOG_INFO);
      }
      
      $msg = "[Success] Script execution successful";
    
    } else {
    
      $syslog = get_config_value ('syslog_on_failure', true);
    
      if ($syslog) {
      
        $error_msg = $GLOBALS['error_msg'];
        $syslog_msg = "[SureDone $plugin_name plugin] [Error] $error_msg";
        $syslog_priority = get_config_value ('syslog_failure_priority', LOG_ERR);
      }
      
      $msg = "[Failure] Script exited with errors";
      break;
    }
  }
  
  
  # perform a syslog if required
  
  if ($syslog) {
  
    syslog ($syslog_priority, $syslog_msg);
    
    $msg = "$msg (logged to syslog)";
    
    $exit_status = 1;
    
  } else {
  
    $msg = "$msg (not logged to syslog)";
    
    $exit_status = 0;
  }
  
  
  # echo the success/failure of the script and exit
  
  echo "$msg\n";
  
  if ($exit_status != 0)
    exit ($exit_status);
}


function  stop () {
  $GLOBALS['error'] = false;
  return  false;
}


?>