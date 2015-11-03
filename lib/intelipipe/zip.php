<?php


function  create_zip_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'create_zip';
  

  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');
  
  
  # get file opt

  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
    
  # get files opt
  
  $files = get_opt ($prefix, $opts, 'files');

  if (!check_opt_if_set_type ($cmd, $files, 'files', 'array'))
    return  false;
  
  
  # get files opt
  
  $message_type_on_zero_files = get_opt_config_value ($prefix, $opts, 'message_type_on_zero_files', 'warning');

  if (!check_opt_if_set_type ($cmd, $message_type_on_zero_files, 'message_type_on_zero_files', 'message_type'))
    return  false;
    
  
  # display message and return if there are no files

  if (!$files) {

    message ($cmd, "no files specified for inclusion in file : $file", $message_type_on_zero_files);
    
    if ($message_type_on_zero_files == 'error')
      return  false;
    
    return  array ('file' => null);
  }
    
    
  # make parent dir
  
  if (!make_dir (dirname ($file)))
    return  false;
  
    
  # create zip
  
  $zip = new ZipArchive;
  
  if (!@$zip->open ($file, ZipArchive::CREATE | ZipArchive::OVERWRITE))
    return  error ($cmd, "could not open file - $file");
    
    
  # add files to archive
  
  foreach ($files as $file_to_zip) {
  
    if (is_string ($file_to_zip)) {
    
      $path = $file_to_zip;
      $name = basename ($file_to_zip);
    
    } elseif (is_array ($file_to_zip)) {
    
      $path = $file_to_zip[0];
      $name = $file_to_zip[1];
    }
  
    if (!$zip->addFile ($path, $name))
      return  error ($cmd, "could not add file : $path => $file");
  }
  
  if (!$zip->close ())
    return  error ($cmd, "could not create file : $file");
    
    
  # display message
  
  $count = count ($files);
  
  debug_echo ($cmd, "zip file created ($count files) : $file");
  
  
  # return
  
  if ($pipe === false)
    return  $file;
    
  return  array ('file' => $file);
}


function  unzip ($opts, $pipe = null, $cmd = __FUNCTION__) {

  
  # set prefix

  $prefix = 'unzip';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');
    
    
  # get file opt
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
    
    
  # get extract to dir opt
  
  $extract_to_dir = get_opt ($prefix, $opts, 'extract_to_dir');
    
  if (!check_opt_if_set_type ($cmd, $extract_to_dir, 'extract_to_dir', 'string'))
    return  false;
    
    
  # get perms opt
  
  $perms = get_opt ($prefix, $opts, 'perms', 0755);
    
  if (!check_opt_set_type ($cmd, $perms, 'perms', 'integer'))
    return  false;
    
    
  # check file exists
 
  if (!is_file ($file))
    return  error ($cmd, "zip file '$file' does not exist");
    
    
  # use cwd if extract to dir doesn't exist
    
  if (!$extract_to_dir) {
  
    $extract_to_dir = getcwd ();
    
    warning ($cmd, "using current working directory to extract to : $extract_to_dir");
  }
  
  
  # create parent directory
  
  if (!make_dir ($extract_to_dir))
    return  false;
    
 
  # extract zip using shell (sometimes there are problems otherwise)
  
  debug_echo ($cmd, "extracting file to dir : $file => $extract_to_dir");
  
  $rv = null;
  $out = null;
  
  $r = exec ("unzip -o -d '$extract_to_dir' '$file'", $out, $rv);

  if ($rv != 0 && strpos ($r, 'inflating:') == false)
      return  error ($cmd, "could not extract zip file to dir : $file => $extract_to_dir");

      
  # change the perms
      
  $iterator = new RecursiveIteratorIterator (new RecursiveDirectoryIterator ($extract_to_dir));

  foreach ($iterator as $item) {
      chmod ($item, $perms);
  }
        
      
  return  array ('dir' => $extract_to_dir);
}


function  unzip_files_in_dir ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'unzip';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');
  

  # get dir opt

  $dir = get_opt ($prefix, $opts, 'dir');
  
  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
    
  
  # get extract to dir opt
  
  $extract_to_dir = get_opt ($prefix, $opts, 'extract_to_dir');
    
  if (!check_opt_if_set_type ($cmd, $extract_to_dir, 'extract_to_dir', 'string'))
    return  false;
        
  
  # use cwd if extract to dir doesn't exist
        
  if (!$extract_to_dir) {
  
    $extract_to_dir = getcwd ();
    
    warning ($cmd, "using current working directory to extract to : $extract_to_dir");
  }
  
  
  # create parent directory
  
  if (!make_dir ($extract_to_dir))
    return  false;
    
    
  # get files
  
  $files = scan_dir ($dir, false);
  
  if ($files === false)
    return  false;
    
  if (count ($files) == 0)
    return  error ($cmd, "no files found in dir : $dir");
    
    
  # unzip all the files
  
  foreach ($files as $file) {
  
    $path = "$dir/$file";
  
    $pass_opts = array (
    
      'file' => $path,
      'extract_to_dir' => $extract_to_dir,
      'make_dir' => false,
    );
  
    $r = unzip ($pass_opts);
    
    if (!$r)
      return  false;
  }
  
  
  # return extract to dir
  
  return  array ('dir' => $extract_to_dir);
}


?>