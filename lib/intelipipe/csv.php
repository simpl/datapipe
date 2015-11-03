<?php


function  create_csv_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'create_csv';


  # merge opts

  $opts = merge_opts ($opts, $pipe, 'file');
 

  # get file opt

  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
    
  # get fields opt
  
  $fields = get_opt ($prefix, $opts, 'fields');
  
  if (!check_opt_set_type ($cmd, $fields, 'fields', 'array_of_strings'))
    return  false;
  
  
  # get data opt
  
  $data = get_opt ($prefix, $opts, 'data');
  
  if (!check_opt_set_type ($cmd, $data, 'data', 'array'))
    return  false;
    
  
  # make parent dir
  
  if (!make_dir ($opts, dirname ($file)))
    return  false;
  
    
  # create temp file
  
  $file_handle = @fopen ('php://temp/csv-out', 'w');
  
  if (!$file_handle)
    return  error ($cmd, "cannot create temp CSV file");
    
    
  # output the fields
  
  if (!fputcsv ($file_handle, $fields))
    return  error ($cmd, "could not output the fields CSV file : $file");
    
    
  # output the data
  
  foreach ($data as $line_no => $line) {
  
    if (!fputcsv ($file_handle, $line))
      return  error ($cmd, "could not output line $line_no to CSV file : $file");
  }
  
  
  # grab contents and close temp file
  
  rewind ($file_handle);
  
  $text = stream_get_contents ($file_handle);
    
  fclose ($file_handle);
  
  
  # create file and 
  
  if (!file_put_contents ($file, $text))
    return  error ($cmd, "cannot store CSV data to file : $file");
  
  
  # report
    
  $lines = count ($data);
    
  debug_echo ($cmd, "CSV file created ($lines lines) : $file");
  
  
  # return
  
  if ($pipe === false)
    return  $file;
  
  return  array ('file' => $file);
}


?>