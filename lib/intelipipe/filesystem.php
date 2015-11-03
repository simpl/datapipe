<?php


function  rmdir_recursive ($dir) {
 
  if (!is_dir ($dir))
    return  true;
 
  $files = array_diff (scandir($dir), array('.','..'));
   
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? rmdir_recursive ("$dir/$file") : unlink("$dir/$file");
  }
  
  return  rmdir ($dir);
}

  
function  clean_dir ($opts, $pipe = false, $cmd = __FUNCTION__) {
     
     
  # set prefix

  $prefix = 'clean_dir';
     
     
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');
  
  
  # get clearn dir opt
  
  $clean_dir = get_opt ($prefix, $opts, 'clean_dir', true);
  
  if (!check_opt_set_type ($cmd, $clean_dir, 'clean_dir', 'boolean'))
    return  false;
  
  if ($clean_dir === false)
    return  true;
    
  
  # get dir opt
    
  $dir = get_opt ($prefix, $opts, 'dir');

  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
  
  
  # clean directory
  
  debug_echo ($cmd, "cleaning local dir : $dir");
  
  if (!rmdir_recursive ($dir)) {
  
    return  error ($cmd, "could not clean local dir : $dir");
  }
  
  return  true;
}


function  make_dir ($opts, $pipe = false, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'make_dir';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');
  

  # get make dir opt
  
  $make_dir = get_opt ($prefix, $opts, 'make_dir', true);
  
  if (!check_opt_set_type ($cmd, $make_dir, 'make_dir', 'boolean'))
    return  false;
  
  if ($make_dir === false)
    return  true;

  
  # get dir opt
    
  $dir = get_opt ($prefix, $opts, 'dir');

  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
  
  
  # check to see if the dir exists
  
  if (is_dir ($dir))
    return   true;
    
  
  # get dir permissions opt
    
  $permissions = get_opt ($prefix, $opts, 'dir_permissions', 0755);
  
  if (!check_opt_set_type ($cmd, $permissions, 'dir_permissions', 'integer'))
    return  false;

    
  # make dir
    
  debug_echo ($cmd, "creating local dir : $dir");  
  
  if (!@mkdir ($dir, $permissions, true))
    return  error ($cmd, "could not create local dir : $dir");
    
  return  true;
}


function  make_dir_for_saving_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix
  
  $prefix = 'make_dir_for_saving_file';
  

  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');
  
  
  # get file opt
    
  $file = get_opt ($prefix, $opts, 'file');

  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
  

  # make the parent dir of the file
  
  if (make_dir (dirname ($file), null, $cmd) === false)
    return  false;
    
    
  # check that we can save to the file

  if (!file_put_contents ($file, ' '))
    return  error ($cmd, "could not save test file - $file");

      
  # delete the file
  
  @unlink ($file);

  
  return  true;
}


function  scan_dir ($opts, $pipe = false, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'scan_dir';

  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');


  # get dir opt

  $dir = get_opt ($prefix, $opts, 'dir');
  
  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
    
    
  # check dir exists
    
  if (!is_dir ($dir))
    return  error ($cmd, "dir does not exist : $dir");
    
    
  # perform scan
    
  $files = @scandir ($dir);
  
  if (!$files)
    return  error ($cmd, "could not scan dir : $dir");
    
    
  # unset . and ..
    
  foreach ($files as $key => $file) { 
      
    if ($file == '.' || $file == '..') {
    
      unset ($files[$key]);
    }
  }
  
  sort ($files);
  
  if ($pipe === false)  // used for internal functions that use scan_dir
    return  $files;
  
  return  array ('dir' => $dir, 'files' => $files);
}


function  backup_file ($opts, $pipe = null, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'file_backup';
  
  
  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');
  
  
  # get backup file opt

  $backup_file = get_opt ($prefix, $opts, 'backup_file', true);
  
  if (!check_opt_set_type ($cmd, $backup_file, 'backup_file', 'boolean'))
    return  false;
  
  if ($backup_file === false)
    return  true;
  
  
  # get file opt
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
      
  
  # get suffix opt
  
  $suffix = get_opt_config_value ($prefix, $opts, 'suffix', '.bak.');
  
  if (!check_opt_set_type ($cmd, $suffix, 'suffix', 'string'))
    return  false;
  
  
  # create new path
  
  $index = 1;
  
  do {
  
    $new_file = "${file}${suffix}${index}";
    
    if (!is_file ($new_file))
      break;
  
    $index++;
  
  } while (true);
  
  
  # move file
  
  debug_echo ($cmd, "backing up file : $file => $new_file");
  
  if (!rename ($file, $new_file))
    return  error ($cmd, "could not rename file : $file => $new_file");
  
    
  return  array ('file' => $new_file);
}


?>