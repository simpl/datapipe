<?php

# TODO: move this into util/common

function  array_strip_offset_util ($list, $offset, $cmd = __FUNCTION__) {

  foreach ($list as $key => $value) {
  
    if (is_array ($value)) {
    
      //$list[$key] = 
      
    } else {
    
      $list[$key] = substr ((string) $value, $offset);
    }
  }
  
  return  $list;
}


function  array_strip_offset ($opts, $pipe, $cmd = __FUNCTION__) {

  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'offset');
  

  # get the list and offset
  
  if (is_array ($opts)) {
  
    if (array_key_exists ('offset', $opts)) {
      
      $offset = $opts['offset'];
        
    } else {
    
      return  error ($cmd, "no offset defined");
    }
    
    if (array_key_exists ('list', $opts)) {
    
      $list = $opts;
    
    } else {
    
      $list = $pipe;
    }
  
  } else {
  
    $list = $pipe;
    $offset = (int) $opts;
    
    if ($offset <= 0) {
      return  $list;
    }
  }
  
  
  # check validity of list and offset
  
  if (!check_opt_set_type ($cmd, $list, 'list', 'array_of_strings'))
    return  false;
  
  if (!check_opt_set_type ($cmd, $offset, 'offset', 'integer'))
    return  false;
    
    
  # strip offset recursively
  
  $list = array_strip_offset_util ($list, $offset);
  
  return  $list;
}


?>