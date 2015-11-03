<?php


# Note: These functions form a wrapper around the Spyc YAML functions, which are also included


function  yaml_decode ($opts, $pipe = null) {

  $opts = get_opts_or_pipe ($opts, $pipe);

  return  spyc_load ($opts);
}


function  yaml_encode ($opts, $pipe = null, $offset = 0) {

  $opts = get_opts_or_pipe ($opts, $pipe);

  $yaml = spyc_dump ($opts);
  
  if ($offset) {
  
    $offset_str = str_repeat ('  ', $offset);

    $lines = explode ("\n", $yaml);

    for ($i=0; $i<count($lines); $i++) {
    
      $val = $lines[$i];
      
      if (trim ($val) == '')
        continue;
    
      $lines[$i] = "${offset_str}${val}";
    }
    
    $yaml = implode ("\n", $lines);
  }
  
  return  $yaml;
}


function  dump_yaml ($opts, $pipe = null, $offset = 0) {

  $opts = get_opts_or_pipe ($opts, $pipe);
  
  if (is_int ($pipe))
    $offset = $pipe;
  
  if ($pipe === true)
    $offset = $GLOBALS['yaml_indent'];
  
  if ($offset == 0) {
  
    message ('dump_yaml', ':', 'echo');
    
    $offset = 1;
  }
  
  echo  yaml_encode ($opts, null, $offset);
  
  return  $opts;
}


function  debug_dump_yaml ($opts, $pipe = null, $offset = 0) {

  if (debug_mode ())
    return  dump_yaml ($opts, $pipe, $offset);
    
  return  get_opts_or_pipe ($opts, $pipe);
}


?>