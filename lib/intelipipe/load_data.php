<?php


function  load_data_line ($line) {

  foreach ($line as $key => $value) {
  
    $lower = strtolower ($value);
  
    switch ($lower) {
    
      case  'null'    :   $line[$key] = null;   break;
      case  'true'    :   $line[$key] = true;   break;
      case  'false'   :   $line[$key] = false;  break;
    }
    
    // TODO: add settings
  }

  return  $line;
}


function  load_data_from_csv_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'load_data_from_csv_file';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');


  # get csv file
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
    
  # get delimiter
  
  $delimiter = get_opt_config_value ($prefix, $opts, 'delimiter', ',');
  
  if (!check_opt_set_type ($cmd, $delimiter, 'delimiter', 'string'))
    return  false;

  if (strlen ($delimiter) != 1)
    return  error ($cmd, "delimiter must be 1 character");
    
  
  # get trim
  
  $trim = get_opt_config_value ($prefix, $opts, 'trim', true);
    
  if (!check_opt_set_type ($cmd, $trim, 'trim', 'boolean'))
    return  false;

    
  # get defined fields opt
  
  $defined_fields = get_opt ($prefix, $opts, 'defined_fields');
  
  if (!check_opt_if_set_type ($cmd, $defined_fields, 'defined_fields', 'array_of_strings'))
    return  false;

  
  # get check fields opt
    
  $check_fields = get_opt_config_value ($prefix, $opts, 'check_fields', false);
  
  if (!check_opt_if_set_type ($cmd, $check_fields, 'check_fields', 'boolean'))
    return  false;
    
    
  # get save_fields opt
  
  $save_fields = get_opt ($prefix, $opts, 'save_fields');
  
  if (!check_opt_if_set_type ($cmd, $save_fields, 'save_fields', 'array_of_strings'))
    return  false;
    

  # get limit opt
  
  $limit = get_opt ($prefix, $opts, 'limit', 0);
  
  if (!check_opt_set_type ($cmd, $limit, 'limit', 'positive_integer'))
    return  false;


  # get offset opt
  
  $offset = get_opt ($prefix, $opts, 'offset', 0);
  
  if (!check_opt_set_type ($cmd, $offset, 'offset', 'positive_integer'))
    return  false;
    
    
  # check if file exists
  
  if (!file_exists ($file))
    return  error ($cmd, "file does not exist : $file");
    
    
  # load file
  
  $file_handle = @fopen ($file, 'r');
  
  if (!$file_handle)
    return  error ($cmd, "cannot read file : $file");
    
  debug_echo ($cmd, "creating data from CSV file : $file");
    
    
  # set up input fields
    
  if ($defined_fields) {
    $defined_fields_source = '(from the config)';
  } else {
    $defined_fields = fgetcsv ($file_handle, 0, $delimiter);
    $defined_fields_source = '(from the source file)';
  }
  
  
  # set up response field indexes
  
  if ($save_fields) {
  
    $res_fields = $save_fields;
    $res_field_count = count ($res_fields);
    
    $res_field_indexes = build_field_indexes ($defined_fields, $res_fields, $cmd);
  
    if ($res_field_indexes === false)
      return;
    
  } else {
  
    $res_fields = $defined_fields;
  }
  
  
  # display info about csv extraction
  
  debug_echo ($cmd, "defined fields $defined_fields_source :");
  debug_dump_list ($defined_fields, true);
  
  if ($save_fields) {
      debug_echo ($cmd, "saved fields :");
      debug_dump_list ($save_fields, true);
  }
  
  
  # parse the offset
  
  for ($off_i=0; $off_i<$offset; $off_i++) {
    
    if (feof ($file_handle))
      break;
  
    fgets ($file_handle);
  }


  # notice about offset reaching
  
  if ($offset > 0 && !feof ($file_handle)) {
    notice ($cmd, "offset ($offset) reached");
  }
  
  
  # build the data

  $limit_i = 0;
  $line_no = $offset;
  $res_data = array ();
  
  if ($save_fields) {
  
    while (!feof ($file_handle)) {
        
      $line_no++;
      
    
      # parse the line data and check it's valid
    
      $line = fgetcsv ($file_handle, 0, $delimiter);
      
      if ($line === false || count ($line) == 0)
        continue;

        
      # check if we're trimming
        
      if ($trim) {
      
        foreach ($line as $i => $val) {
        
          $line[$i] = trim ($val);
        }
      }
        
        
      # create new line data from saved fields
      
      $new_line = array ();

      for ($i=0; $i<$res_field_count; $i++) {
      
        $index = $res_field_indexes[$i];
        
        if (!isset ($line[$index])) {
        
          $line_json = json_encode ($line);
        
          warning ($cmd, "index '$index' does not exist for line_no $line_no (skipping line) : $line_json");
          break;
        }
      
        $new_line[] = $line[$index];
      }

      
      $res_data[] = $new_line;    # load_data_line ()
      
      
      # check the limit
      
      if ($limit != 0) {
      
        $limit_i++;
        
        if ($limit_i >= $limit) {
          notice ($cmd, "limit ($limit) reached");
          break;
        }
      }
    }
  
  } elseif ($check_fields) {
  
  
    $defined_field_count = count ($defined_fields);
  
    while (!feof ($file_handle)) {  
      
      $line_no++;
      
      
      # check the line is valid, and add it to the data

      $line = fgetcsv ($file_handle, 0, $delimiter);
      
      if ($line === false || count ($line) == 0)
        continue;
              
              
      # check if we're trimming
        
      if ($trim) {
      
        foreach ($line as $i => $val) {
        
          $line[$i] = trim ($val);
        }
      }
      
      
      # check line count
      
      if (count ($line) < $defined_field_count) {
            
        $line_count = count ($line);
            
        $line_json = json_encode ($line);
            
        warning ($cmd, "line no $line_no has fewer fields ($line_count) than the defined number of fields ($defined_field_count), skipping line : $line_json");
        continue;
      }
              
      $res_data[] = $line;    # load_data_line ()

      
      # check the limit
      
      if ($limit != 0) {
      
        $limit_i++;
        
        if ($limit_i >= $limit) {
          notice ($cmd, "limit ($limit) reached");
          break;
        }
      }
    }
    
  } else {
  
    while (!feof ($file_handle)) {  
      
      
      # check the line is valid, and add it to the data

      $line = fgetcsv ($file_handle, 0, $delimiter);
      
      if ($line === false || count ($line) == 0)
        continue;
              
              
      # check if we're trimming
        
      if ($trim) {
      
        foreach ($line as $i => $val) {
        
          $line[$i] = trim ($val);
        }
      }
      
      
      # add line
      
      $res_data[] = $line;    # load_data_line ()

      
      # check the limit
      
      if ($limit != 0) {
      
        $limit_i++;
        
        if ($limit_i >= $limit) {
          notice ($cmd, "limit ($limit) reached");
          break;
        }
      }
    }
  }


  # display info about reaching the end of file
  
  if (feof ($file_handle)) {
  
    $total_lines = $off_i + $limit_i;
  
    notice ($cmd, "end of file reached after $total_lines lines of data (offset = $offset, limit = $limit)");
  }
  
  fclose ($file_handle);

  
  # detail results
  
  $line_count = count ($res_data);
  
  debug_echo ($cmd, "creation of data from CSV file complete ($line_count lines processed)");
  
  
  # create result
  
  return  build_result_data ($res_fields, $res_data);
}


function  load_data_from_lengths_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'load_data_from_lengths_file';


  # merge opts

  $opts = merge_opts ($opts, $pipe, 'file');


  # get file opt

  $file = get_opt ($prefix, $opts, 'file');

  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
  
  # get defined fields opt

  $defined_fields = get_opt ($prefix, $opts, 'defined_fields');

  if (!check_opt_if_set_type ($cmd, $defined_fields, 'defined_fields', 'array_of_strings,yaml_array_of_strings'))
    return  false;

  if (is_string ($defined_fields))
    $defined_fields = yaml_decode ($defined_fields);
    
   
  # get save fields

  $save_fields = get_opt ($prefix, $opts, 'save_fields');

  if (!check_opt_if_set_type ($cmd, $save_fields, 'save_fields', 'array_of_strings,yaml_array_of_strings'))
    return  false;
    
  if (is_string ($save_fields))
    $save_fields = yaml_decode ($save_fields);
    
   
  # get splits opt
  
  $splits = get_opt ($prefix, $opts, 'splits');
  
  if (!check_opt_if_set_type ($cmd, $splits, 'splits', 'array_of_integers,yaml_array_of_integers'))
    return  false;
    
  if (is_string ($splits))
    $splits = yaml_decode ($splits);
    
    
  # get lengths opt
  
  $lengths = get_opt ($prefix, $opts, 'lengths');
  
  if (!check_opt_if_set_type ($cmd, $lengths, 'lengths', 'array_of_integers,yaml_array_of_integers'))
    return  false;
    
  if (is_string ($lengths))
    $lengths = yaml_decode ($lengths);
    
    
  # get fields lengths opt
  
  $fields_lengths = get_opt ($prefix, $opts, 'fields_lengths');

  if (!check_opt_if_set_type ($cmd, $fields_lengths, 'fields_lengths', 'array,yaml_array'))
    return  false;
    
  if (is_string ($fields_lengths))
    $fields_lengths = yaml_decode ($fields_lengths);
    
    
  # get fields splits opt
  
  $fields_splits = get_opt ($prefix, $opts, 'fields_splits');
  
  if (!check_opt_if_set_type ($cmd, $fields_splits, 'fields_splits', 'array,yaml_array'))
    return  false;
    
  if (is_string ($fields_splits))
    $fields_splits = yaml_decode ($fields_splits);
    

  # get trim opt (i.e. whether the values should have leading and trailing whitespace removed)
  
  $trim = get_opt_config_value ($prefix, $opts, 'trim', true);
  
  if (!check_opt_set_type ($cmd, $trim, 'trim', 'boolean'))
    return  false;
  
  
  # get limit opt
  
  $limit = get_opt ($prefix, $opts, 'limit', 0);
  
  if (!check_opt_set_type ($cmd, $limit, 'limit', 'positive_integer'))
    return  false;
    

  # get offset opt
  
  $offset = get_opt ($prefix, $opts, 'offset', 0);
  
  if (!check_opt_set_type ($cmd, $offset, 'offset', 'positive_integer'))
    return  false;
  
  
  # check file exists
  
  if (!file_exists ($file))
    return  error ($cmd, "file does not exist : $file");
  
  
  # open file
  
  $file_handle = fopen ($file, 'r');
  
  if (!$file_handle)
    return  error ($cmd, "cannot read file : $file");
    
  debug_echo ($cmd, "creating data from column file : $file");
  
  
  # check for splits/lengths fields
  
  if ($fields_splits) {

    $defined_fields = array_keys ($fields_splits);
    $splits = array_values ($fields_splits);
    
  } elseif ($fields_lengths) {
  
    
  
    $defined_fields = array_keys ($fields_lengths);
    $lengths = array_values ($fields_lengths);
  }
  
  
  # check for lengths or splits
  
  if ($lengths) {
  
    $total = 0;
    $splits = array ();
  
    foreach ($lengths as $length) {
    
      $total += $length;
      $splits[] = $total;
    }
  
  } elseif (!$splits) {
  
    return  opt_not_set_msg ($cmd, "lengths,splits,fields_lengths,fields_splits");
  }
  
    
  # set up splits
  
  $split_count = count ($splits);
  
  
  # get the fields from list or file
  
  if ($defined_fields) {
  
    $defined_fields_source = '(from the config)';
    
    $field_count = count ($defined_fields);
    
    if ($field_count != $split_count)
      return  error ($cmd, "the field count ($field_count) is not the same as the split count ($split_count)");
  
  } else {
  
    $defined_fields = array ();
    
    $line = fgets ($file_handle);
    
    for ($i=0; $i<$split_count; $i++) {
    
      $min = $splits[$i];
    
      if ($i == 0) {
      
        $min = 0;
        $len = $splits[0];
      
      } else {
      
        $min = $splits[$i-1];
        $len = ($splits[$i] - $min);
      }
    
      $val = substr ($line, $min, $len);
      
      if ($trim)
        $val = trim ($val);
      
      $defined_fields[] = $val;
    }
    
    $defined_fields_source = '(from the source file)';
  }
  
  
  # set up response field indexes
  
  if ($save_fields) {
  
    $res_fields = $save_fields;
    $res_field_count = count ($res_fields);
    
    $res_field_indexes = build_field_indexes ($defined_fields, $res_fields, $cmd);

    if ($res_field_indexes === false)
      return;
    
  } else {
  
    $res_fields = $defined_fields;
  }
  
  
  # display info
  
  debug_echo ($cmd, "splits used for dividing data : " . json_encode ($splits));
  debug_echo ($cmd, "defined fields $defined_fields_source : ");
  debug_dump_list ($defined_fields, true);
  
  if ($save_fields) {
      debug_echo ($cmd, "saved fields :");
      debug_dump_list ($save_fields, true);
  }
  
  
  # parse the offset
  
  for ($off_i=0; $off_i<$offset; $off_i++) {
    
    if (feof ($file_handle))
      break;
  
    fgets ($file_handle);
  }


  # notice about offset reaching
  
  if ($offset > 0 && !feof ($file_handle)) {
    notice ($cmd, "offset ($offset) reached");
  }
  
  
  # loop over lines to split the file
  
  $limit_i = 0;
  $res_data = array ();

  if ($save_fields) {
  
    while ($line = fgets ($file_handle)) {
    

      # parse all the fields
    
      $line_data = array ();
    
      for ($i=0; $i<$split_count; $i++) {
      
        $min = $splits[$i];
      
        if ($i == 0) {
        
          $min = 0;
          $len = $splits[0];
        
        } else {
        
          $min = $splits[$i-1];
          $len = ($splits[$i] - $min);
        }
      
        $val = substr ($line, $min, $len);
        
        if ($trim)
          $val = trim ($val);
        
        $line_data[] = $val;
      }
      
      
      # save just the save fields
      
      $new_line = array ();
      
      for ($i=0; $i<$res_field_count; $i++) {
      
        $new_line[] = $line_data[$res_field_indexes[$i]];
      }
      
      $res_data[] = $new_line;  # load_data_line ()
      
      
      # check the limit
      
      if ($limit != 0) {
      
        $limit_i++;
        
        if ($limit_i >= $limit) {
          notice ($cmd, "limit ($limit) reached");
          break;
        }
      }
    }
  
  } else {
  
    while ($line = fgets ($file_handle)) {
    

      # parse all the fields
      
      $line_data = array ();
    
      for ($i=0; $i<$split_count; $i++) {
      
        $min = $splits[$i];
      
        if ($i == 0) {
        
          $min = 0;
          $len = $splits[0];
        
        } else {
        
          $min = $splits[$i-1];
          $len = ($splits[$i] - $min);
        }
      
        $val = substr ($line, $min, $len);
        
        if ($trim)
          $val = trim ($val);
        
        $line_data[] = $val;
      }
      
      $res_data[] = $line_data;   # load_data_line ()
      
      
      # check the limit
      
      if ($limit != 0) {
      
        $limit_i++;
        
        if ($limit_i >= $limit) {
          notice ($cmd, "limit ($limit) reached");
          break;
        }
      }
    }
  }
  
  
  # display info about reaching the end of file
  
  if (feof ($file_handle)) {
  
    $total_lines = $off_i + $limit_i;
  
    notice ($cmd, "end of file reached after $total_lines lines of data (offset = $offset, limit = $limit)");
  }
  
  fclose ($file_handle);
  
  
  # detail results
  
  $line_count = count ($res_data);
  
  debug_echo ($cmd, "creation of data from column file complete ($line_count lines processed)");
    
  
  # create result

  return  build_result_data ($res_fields, $res_data);
}



function  load_data_from_doc_file ($opts, $pipe, $cmd = __FUNCTION__) {

  # set prefix

  $prefix = $cmd;


  # merge opts

  $opts = merge_opts ($opts, $pipe, 'doc_type');


  # get file opt

  $file = get_opt ($prefix, $opts, 'file');

  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
  
  # get defined fields opt

  $defined_fields = get_opt ($prefix, $opts, 'defined_fields');

  if (!check_opt_if_set_type ($cmd, $defined_fields, 'defined_fields', 'array_of_strings'))
    return  false;

   
  # get save fields

  $save_fields = get_opt ($prefix, $opts, 'save_fields');

  if (!check_opt_if_set_type ($cmd, $save_fields, 'save_fields', 'array_of_strings'))
    return  false;


  # get doc type opt

  $doc_type = get_opt ($prefix, $opts, 'doc_type', 'json');

  if (!check_opt_set_type ($cmd, $doc_type, 'doc_type', 'document_file_type'))
    return  false;
    
  $doc_type_upper = strtoupper ($doc_type);
    
    
  # get data structure opt
 
  $data_structure = get_opt ($prefix, $opts, 'data_structure', 'list_of_objects');

  if (!check_opt_set_type ($cmd, $data_structure, 'data_structure', 'data_structure_type'))
    return  false;
    
    
  # get limit opt
  
  $limit = get_opt ($prefix, $opts, 'limit', 0);
  
  if (!check_opt_set_type ($cmd, $limit, 'limit', 'positive_integer'))
    return  false;
    

  # get offset opt
  
  $offset = get_opt ($prefix, $opts, 'offset', 0);
  
  if (!check_opt_set_type ($cmd, $offset, 'offset', 'positive_integer'))
    return  false;
    
  
  # check the file exists
  
  if (!file_exists ($file))
    return  error ($cmd, "file does not exist : $file");
    
    
  # display generic info about the data
    
  $data_structure_str = str_replace ('_', ' ', $data_structure);
    
  debug_echo ($cmd, "creating data from $doc_type_upper file : $file");
  debug_echo ($cmd, "source data of type '$data_structure_str'");
    
    
  # read the file into memory
  
  $data_str = @file_get_contents ($file);
  
  if (!is_string ($data_str))
    return  error ($cmd, "could not read file : $file");
    
    
  # load data depending on type
  
  switch ($doc_type) {
  
    case  'json' :    $data = @json_decode ($data_str, true);   break;
    case  'yaml' :    $data = @yaml_decode ($data_str);         break;
  }

  if (!$data)
    return  error ($cmd, "invalid $doc_type_upper in file : $file");
    
  $data_count = count ($data);
  
  
  # set up end point
  
  if ($limit == 0) {
    $end = $data_count;
  } else {
    $end = $offset + $limit;
  }
  
  
  # set up the fields and build data
  
  $res_data = array ();
  
  switch ($data_structure) {
  
    case  'list_of_columns' :
  
      # set up fields
  
      if ($defined_fields) {
      
        $defined_fields_source = '(from the config)';
        
      } else {
      
        $defined_fields = array_shift ($data);
        $defined_fields_source = '(from the source file)';
      }
  
  
      # set up response field indexes
    
      if ($save_fields) {
      
        $res_fields = $save_fields;
        $res_field_count = count ($res_fields);
        
        $res_field_indexes = build_field_indexes ($defined_fields, $res_fields, $cmd);

        if ($res_field_indexes === false)
          return;
        
      } else {
      
        $res_fields = $defined_fields;
      }
  
  
      # display info about the fields
    
      debug_echo ($cmd, "defined fields $defined_fields_source : ");
      debug_dump_list ($defined_fields, true);
      
      if ($save_fields) {
          debug_echo ($cmd, "saved fields :");
          debug_dump_list ($save_fields, true);
      }
  
  
      # gather the data
  
      # TODO: change for having offset / limit
  
      $res_data = array ();
  
      if ($save_fields) {
  
        $res_data = array ();
  
        for ($i=$offset; $i<$end; $i++) {
        
          $line = $data[$i];
          $new_line = array ();
      
          for ($j=0; $j<$res_field_count; $j++) {
          
            $new_line[] = $line[$res_field_indexes[$j]];
          }
          
          $res_data[] = $new_line;
        }
        
      } elseif ($offset == 0 && $limit == 0) {
      
        $res_data = $data;
        
      } else {
        
        $res_data = array ();
  
        for ($i=$offset; $i<$end; $i++) {
        
          $res_data[] = $data[$i];
        }
      }
      
      break;
      
  
    case  'list_of_objects' :
        
       
      $res_data = array ();
       
      if ($save_fields) {
      
      
        # set up result fields
      
        $res_fields = $save_fields;
        $res_fields_count = count ($res_fields);
      
      
        # display info about what fields will be saved
      
        debug_echo ($cmd, "saved fields :");
        debug_dump_list ($res_fields, true);
             
      
        # build the data
      
        for ($i=$offset; $i<$end; $i++) {
        
          $obj = $data[$i];
          $new_line = array ();
          
          for ($j=0; $j<$res_fields_count; $j++) {
          
            $new_line[] = @$obj[$res_fields[$j]];
          }
        
          $res_data[] = $new_line;
        }
      
        $res_fields = $save_fields;
      
      } else {
    
    
        # display info about saved files
      
        debug_echo ($cmd, "saving all fields - they will be listed as they are added");
      
      
        # build the data
    
        $res_fields = array ();
        $res_fields_count = 0;
    
        for ($i=$offset; $i<$end; $i++) {

          $obj = $data[$i];
          $new_line = array ();
          
    
          # save all the existing data in order

          for ($j=0; $j<$res_fields_count; $j++) {
            
            $new_line[] = @$obj[$res_fields[$j]];
          }
        
        
          # index and save any new fields
        
          $res_fields_added = array ();
        
          foreach ($obj as $key => $value) {
          
            if (in_array ($key, $res_fields))
              continue;
            
            $res_fields_added[] = $key;
            $res_fields[] = $key;
            $res_fields_count++;
            
            $new_line[] = $value;
          }

          $res_data[$i] = $new_line;
          
          
          # display list of fields added (if any)
        
          if ($res_fields_added) {
            $line_no = $i+1;
            debug_echo ($cmd, "the following fields were added on row $line_no :");
            debug_dump_list ($res_fields_added, true);
          }
        }
        
        
        # add back in any empty fields
        
        for ($i=0; $i<count ($res_data); $i++) {
        
          $line = &$res_data[$i];
          
          $line_fields_count = count ($line);
          
          if ($line_fields_count == $res_fields_count)
            break;
        
          for ($j=$line_fields_count; $j<$res_fields_count; $j++) {
          
            $line[] = '';
          }
        }
      }
      
      break;
  }

  
  # detail results
  
  $line_count = count ($res_data);
  
  debug_echo ($cmd, "creation of data from JSON file complete ($line_count lines processed)");
    
  
  # create result
  
  return  build_result_data ($res_fields, $res_data);
}


function  load_data_from_json_file ($opts, $pipe, $cmd = __FUNCTION__) {
  
  $opts = merge_opts ($opts, $pipe);
  
  return  load_data_from_doc_file ($opts, 'json', $cmd);
}


function  load_data_from_yaml_file ($opts, $pipe, $cmd = __FUNCTION__) {
  
  $opts = merge_opts ($opts, $pipe);
  
  return  load_data_from_doc_file ($opts, 'yaml', $cmd);
}


?>