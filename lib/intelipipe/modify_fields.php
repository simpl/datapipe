<?php


function  modify_fields ($opts, $pipe, $cmd = __FUNCTION__, $opt_prefix = false) {


  # set prefix
  
  $prefix = 'modify_fields';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  
  
  # adjust opt prefix

  $cmd = adjust_opt_prefix ($cmd, $opts, $opt_prefix, 'add_fields');
  
    
  # get data opt

  $data = &get_opt_config_value ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;
  
  
  # get fields opt

  $fields = get_opt_config_value ($prefix, $opts, 'fields');
  
  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
  
  
  # get indexes fields opt

  $indexes = &get_opt_config_value ($prefix, $opts, 'indexes');
  
  if (!check_opt_if_set_type ($cmd, $indexes, 'indexes', 'array'))
    return  false;
  
  
  # get map fields opt

  $map_fields = get_opt_config_value ($prefix, $opts, 'map');
  
  if (!check_opt_if_set_type ($cmd, $map_fields, 'map', 'string,array'))
    return  false;
    
  if (is_string ($map_fields))
    $map_fields = yaml_decode ($map_fields);
    
    
  # get swap fields opt

  $swap_fields = get_opt_config_value ($prefix, $opts, 'swap');
  
  if (!check_opt_if_set_type ($cmd, $swap_fields, 'swap', 'string,array'))
    return  false;
    
  if (is_string ($swap_fields))
    $swap_fields = yaml_decode ($swap_fields);
    
  
  # get add fields opt

  $add_fields = get_opt_config_value ($prefix, $opts, 'add');
  
  if (!check_opt_if_set_type ($cmd, $add_fields, 'add', 'array'))
    return  false;
    
  
  # get delete fields opt

  $delete_fields = get_opt_config_value ($prefix, $opts, 'delete');
  
  if (!check_opt_if_set_type ($cmd, $delete_fields, 'delete', 'array_of_strings'))
    return  false;
    
  
  # get reorder fields opt

  $reorder_fields = get_opt_config_value ($prefix, $opts, 'reorder');
  
  if (!check_opt_if_set_type ($cmd, $reorder_fields, 'reorder', 'array_of_strings'))
    return  false;
    
  
  # perform the operations

  if ($map_fields && !map_data_fields ($data, $fields, $map_fields, $cmd))
    return  false;

  if ($swap_fields && !swap_data_fields ($fields, $swap_fields, $cmd))
    return  false;
    
  if ($add_fields && !add_data_fields ($data, $fields, $add_fields, $cmd))
    return  false;
       
  if ($delete_fields && !delete_data_fields ($data, $fields, $delete_fields, $cmd))
    return  false;

  if ($reorder_fields && !reorder_data_fields ($data, $fields, $reorder_fields, $cmd))
    return  false;
    
  # create indexes

/*
var_dump ($fields);  
var_dump ($data[0]);
exit;
*/
  
  # set the response  
  
  return  build_result_data ($fields, $data, $indexes);
}


function  swap_data_fields (&$fields, $swap_fields, $cmd) {
 
  
  # display info about the map used
  
  debug_echo ($cmd, "swapping data fields using the following map :");
  debug_dump_yaml ($swap_fields, true);
  
  
  # loop over fields
  
  $unswapped_fields = array ();
  
  for ($i=0; $i<count ($fields); $i++) {
  
    $field = $fields[$i];
    
    $swap_field = @$swap_fields[$field];
    
    if ($swap_field) {
      $fields[$i] = $swap_field;
      
    } else {
      $unswapped_fields[] = $field;
    }
  }
  
  
  # display info about which fields were swapped
  
  if ($unswapped_fields) {
  
    debug_echo ($cmd, "the following fields were ignored in the swap :");
    debug_dump_yaml ($unswapped_fields, true);
  }
  
  return  true;
}
  

function  map_data_fields (&$data, &$fields, $map_fields, $cmd) {
 

  # sort fields to map / not
  
  $new_map_fields = array ();
  $ignore_fields = array ();
  
  foreach ($map_fields as $orig => $new) {
  
    if ($new) {
    
      $new_map_fields[$orig] = $new;
    
    } else {
    
      $ignore_fileds[] = $orig;
    }
  }
 
  $map_fields = $new_map_fields;
 
 
  # set up new field indexes

  $index_fields = array_keys ($map_fields);
  
  $field_indexes = build_field_indexes ($fields, $index_fields, $cmd);
  
  if ($field_indexes === false)
    return  false;
  
  
  # display info about the map
  
  debug_echo ($cmd, "mapping data fields using the following map :");
  debug_dump_yaml ($map_fields, true);
  
  if ($ignore_fields) {
  
    debug_echo ($cmd, "ignoring the following empty result fields in the map :");
    debug_dump_yaml ($ignore_fields, true);
  }
  
  
  # set up new fields
  
  $fields = array_values ($map_fields);
  $field_count = count ($field_indexes);
  
  
  # build the data
  
  $data_count = count ($data);
  
  for ($i=0; $i<$data_count; $i++) {
  
    $line = $data[$i];
    $new_line = array ();
    
    for ($j=0; $j<$field_count; $j++) {
    
      $new_line[] = $line[$field_indexes[$j]];
    }
    
    $data[$i] = $new_line;
  }


  return  true;
}
  

function  add_data_fields (&$data, &$fields, $add_fields, $cmd) {


  # display message about fields to be added and their defaults
  
  $add_fields_list = array ();
  
  foreach ($add_fields as $name => $default) {
  
    if ($default) {
    
      $add_fields_list[] = "$name = $default";
    
    } else {
    
      $add_fields_list[] = $name;
    }
  }
  
  debug_echo ($cmd, "adding the following fields to the data :");
  debug_dump_list ($add_fields_list, true);
  
  
  # set up counts
  
  $fields_count = count ($fields);
  $add_fields_count = count ($add_fields);
  
  
  # build new fields and defaults
  
  $new_fields = array ();
  
  foreach ($add_fields as $name => $default) {
    $new_fields[] = $name;
    $new_defaults[] = $default;
  }
  
  foreach ($fields as $field) {
    $new_fields[] = $field;
  }
  
  $fields = $new_fields;
  
  
  # build the new data
  
  $data_count = count ($data);
  
  
  for ($i=0; $i<$data_count; $i++) {

    $line = $data[$i];
    $new_line = array ();
    
    for ($j=0; $j<$add_fields_count; $j++) {
      $new_line[] = $new_defaults[$j];
    }
    
    for ($j=0; $j<$fields_count; $j++) {
      $new_line[] = $line[$j];
    }
    
    $data[$i] = $new_line;
  }
  
  return  true;
}
  
  
  
function  delete_data_fields (&$data, &$fields, $delete_fields, $cmd) {


  # check all the delete fields to check they exist
  
  foreach ($delete_fields as $field) {
  
    if (!in_array ($field, $fields))
      return  error ($cmd, "field '$field' cannot be deleted from the data because it does not exist");
  }
  
  
  # display message about fields to be deleted
    
  debug_echo ($cmd, "deleting the following fields from the data :");
  debug_dump_list ($delete_fields, true);
  
  
  # set up counts
  
  $fields_count = count ($fields);
  $data_count = count ($data);
  
  
  # build the indexes to be deleted
  
  $delete_indexes = array ();
  $new_fields = array ();
  
  for ($i=0; $i<count($fields); $i++) {
  
    $field = $fields[$i];
    
    if (in_array ($field, $delete_fields)) {
    
      $delete_indexes[$i] = true;
      
    } else {
    
      $delete_indexes[$i] = false;
      $new_fields[] = $field;
    }
  }

  $fields = $new_fields;
  

  # build the new data
  
  for ($i=0; $i<$data_count; $i++) {
  
    $line = $data[$i];
    $new_line = array ();
    
    for ($j=0; $j<$fields_count; $j++) {
    
      if ($delete_indexes[$j])
        continue;
        
      $new_line[] = $line[$j];
    }
    
    $data[$i] = $new_line;
  }
  
  return  true;
}


  
  
function  reorder_data_fields (&$data, &$fields, $reorder_fields, $cmd) {


  $new_fields = array ();
  

  # check all the fields to re-order
  
  foreach ($reorder_fields as $field) {
  
    if (!in_array ($field, $fields))
      return  error ($cmd, "field '$field' cannot be reordered from the data because it does not exist");
      
    $new_fields[] = $field;
  }
  
  
  # include all the original fields that are not re-ordered
  
  foreach ($fields as $field) {
  
    if (!in_array ($field, $new_fields))
      $new_fields[] = $field;
  }
  

  # display message
  
  debug_echo ($cmd, "new order for fields :");
  debug_dump_list ($new_fields, true);
  
  
  # set up field indexes
  
  $fields_flipped = array_flip ($fields);
  
  $new_fields_indexes = array ();
  
  foreach ($new_fields as $field) {
  
    $new_fields_indexes[] = $fields_flipped[$field];
  }

  
  # reset the fields
  
  $field_count = count ($fields);
  
  for ($i=0; $i<$field_count; $i++) {
  
    $fields[$i] = $new_fields[$i];
  }
  

  # loop through the data
  
  $data_count = count ($data);
  
  for ($i=0; $i<$data_count; $i++) {
  
    $line = $data[$i];
    
    $new_line = array ();
    
    for ($j=0; $j<$field_count; $j++) {
    
      $new_line[] = $line[$new_fields_indexes[$j]];
    }
    
    $data[$i] = $new_line;
  }
  
  
  return  true;
}

  
?>