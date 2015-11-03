<?php



function  dump_json ($opts, $pipe = null, $cmd = __FUNCTION__) {

  $opts = get_opts_or_pipe ($opts, $pipe);

  echo  json_encode ($opts, null);
  
  return  $opts;
}


function  debug_dump_json ($opts, $pipe = null, $cmd = __FUNCTION__) {

  if (debug_mode ())
    return  dump_json ($opts, $pipe);
    
  return  get_opts_or_pipe ($opts, $pipe);
}


?>