<?php


function  build_indexes ($opts, $pipe, $cmd = __FUNCTION__, $msg_suffix = null) {


  # set prefix
  
  $prefix = 'build_indexes';


  # merge opts
  
  if ($pipe !== null) {
    $opts = merge_opts ($opts, $pipe, 'indexes');
  }

  
  # get fields opt

  $fields = get_opt ($prefix, $opts, 'fields');

  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
  
  
  # get data opt
  
  $data = &get_opt ($prefix, $opts, 'data');

  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;

  
  # get indexes opt
  
  $indexes = &get_opt ($prefix, $opts, 'indexes');

  if (!check_opt_set_type ($cmd, $indexes, 'indexes', 'array_of_strings'))
    return  false;
    
    
  # set up indexes

  $res_indexes_assoc = array ();
  $res_indexes_list = array ();
  
  foreach ($indexes as $index_name) {
  
    $index = array ();
  
    $res_indexes_assoc[$index_name] = &$index;
    $res_indexes_list[] = &$index;
  }
  
    
  # set up field indexes
  
  $field_indexes = build_field_indexes ($fields, $indexes, $cmd, $msg_suffix);

  if ($field_indexes === false)
    return  false;
  
  $index_count = count ($indexes);
  
    
  # loop over data to build indexes
  
  for ($i=0; $i<count ($data); $i++) {

    unset ($line);
  
    $line = &$data[$i];
    
    for ($j=0; $j<$index_count; $j++) {

      $idx = $field_indexes[$j];
    
      $key = $line[$idx];

      $res_indexes_list[$j][$key] = &$line;
    }
  }

  
  # sort the indexes
  
  foreach ($res_indexes_assoc as $name => $index) {
    ksort ($index);
  }
  
  
  # save the indxes
  
  $opts['indexes'] = $res_indexes_assoc;
    
  return  $opts;
}


function  build_field_indexes ($fields, $index_fields, $cmd, $msg_suffix = null) {

  
  # invert the fields to make it easier
  
  $inv_fields = array_flip ($fields);

  
  # loop through the keys
  
  $field_indexes = array ();

  foreach ($index_fields as $field) {
  
    $index = @$inv_fields[$field];
    
    if ($index === null) {
    
      $msg = "no '$field' field exists";
      
      if ($msg_suffix)
        $msg .= " $msg_suffix suffix";
    
      return  error ($cmd, $msg);
    }
    
    $field_indexes[] = $index;
  }
  
  return  $field_indexes;
}


?>