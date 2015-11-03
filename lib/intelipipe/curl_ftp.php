<?php


function  ftp_get_file ($opts, $pipe, $cmd = __FUNCTION__) {
  
  return  curl ($opts, $pipe, $cmd, 'ftp');
}


function  ftp_scandir ($opts, $pipe, $cmd = __FUNCTION__) {


  # set up functions for curl to parse

  $req = array (
  
    'curlopts' => array (
    
      'ftplistonly' => true,
    ),
    
    'finalize_functions' => array (
    
      'curl_finalize_set_split_key',
    ),
    
    'finalize_curlopts' => array (
    
      'ftplistonly' => false,
    ),
    
    'path_is_dir' => true,
  );

  
  # merge the opts and pass to curl for processing
  
  $opts = merge_opts_for_output ($opts, $req);
  
  return  curl ($opts, $pipe, $cmd, 'ftp');
}


function  ftp_get_files ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = $cmd;
  

  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'files');
  
  
  # get files opt
   
  $files = get_opt_config_value ($prefix, $opts, 'files');

  if (!check_opt_set_type ($cmd, $files, 'files', 'array_of_strings'))
    return  false;

    
  # get local dir opt
    
  $local_dir = get_opt_config_value ($prefix, $opts, 'local_dir');

  if (!check_opt_if_set_type ($cmd, $local_dir, 'local_dir', 'string'))
    return  false;
  
    
  # loop over files
  
  $res_files = array ();
  
  foreach ($files as $file) {
  
  
    # get the save_to_file value
  
    if ($local_dir) {
    
      $file_name = basename ($file);
      
      $save_to_file = "$local_dir/$file_name";
    
    } else {
    
      $save_to_file = $file;
    }
    
    
    # process request
    
    $req = array (
      'file'          => $file,
      'save_to_file'  => $save_to_file,
    );

    $r = ftp_get_file ($req, $opts, $cmd);
    
    if ($r === false)
      return  false;
      
      
    # add file to return list
      
    $res_files[] = $save_to_file;
  }
    

  return  array ('files' => $res_files);
}


function  ftp_get_files_in_dir ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = $cmd;


  # merge opts

  $opts = merge_opts_for_output ($pipe, $opts);

  
  # get local dir opt
    
  $local_dir = get_opt_config_value ($prefix, $opts, 'local_dir');

  if (!check_opt_if_set_type ($cmd, $local_dir, 'local_dir', 'string'))
    return  false;
  
  
  # scan dir
  
  $res = ftp_scandir ($opts, null, $cmd);
  
  if ($res === false)
    return  false;
    
    
  # set the local dir
  
  $res['local_dir'] = $local_dir;
  
    
  # get files
    
  return  ftp_get_files ($res, $opts);
}

  
    
    

function  ftp_check_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {

  return  curl_check_saved_file ($opts, $pipe, $cmd, 'ftp');
}


function  ftp_use_checked_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {

  return  ftp_use_checked_saved_file ($opts, $pipe, $cmd, 'ftp');
}


?>