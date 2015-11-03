<?php


function  ftp_init ($opts, $pipe, $cmd = __FUNCTION__) {

  
  # set prefix

  $prefix = 'ftp';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'svr');

   
  # get execute opt
  
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);
  
  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
  
  
  # check if we should execute or not
  
  if (!$execute)
    return  true;
  
  
  # get conn opt
  
  $conn_name = get_opt ($prefix, $opts, 'conn', 'default');
  
  if (!check_opt_set_type ($cmd, $conn_name, 'conn', 'string'))
    return  false;
    
  
  # get svr opt

  $svr = get_opt ($prefix, $opts, 'svr');
  
  if (!check_opt_set_type ($cmd, $svr, 'svr', 'string'))
    return  false;

  
  # get port opt
  
  $port = get_opt ($prefix, $opts, 'port', 21);
  
  if (!check_opt_set_type ($cmd, $port, 'conn', 'integer'))
    return  false;
    
  
  # get ssl opt
  
  $ssl = get_opt ($prefix, $opts, 'ssl', false);
  
  if (!check_opt_set_type ($cmd, $ssl, 'ssl', 'boolean'))
    return  false;
    
  
  # get usr opt
  
  $usr = get_opt ($prefix, $opts, 'usr');
  
  if (!check_opt_set_type ($cmd, $usr, 'usr', 'string'))
    return  false;
  
  
  # get pwd opt
  
  $pwd = get_opt ($prefix, $opts, 'pwd');
  
  if (!check_opt_set_type ($cmd, $pwd, 'pwd', 'string'))
    return  false;
  
  
  # get pasv opt
  
  $pasv = get_opt_config_value ($prefix, $opts, 'pasv', true);
  
  if (!check_opt_set_type ($cmd, $pasv, 'pasv', 'boolean'))
    return  false;
    
  
  # get connect retries opt
  
  $connect_retries = get_opt_config_value ($prefix, $opts, 'connect_retries', 3);
  
  if (!check_opt_set_type ($cmd, $connect_retries, 'connect_retries', 'integer'))
    return  false;
    
  
  # get login retries opt
  
  $login_retries = get_opt_config_value ($prefix, $opts, 'login_retries', 3);
  
  if (!check_opt_set_type ($cmd, $login_retries, 'login_retries', 'integer'))
    return  false;
    
    
  # get pasv retries opt

  $pasv_retries = get_opt_config_value ($prefix, $opts, 'pasv_retries', 3);
  
  if (!check_opt_set_type ($cmd, $pasv_retries, 'pasv_retries', 'integer'))
    return  false;
  
  
  # setup connection
  
  debug_echo ($cmd, "connecting to FTP server : $svr ...");
  
  for ($j=0; $j<$connect_retries; $j++) {
    
    if ($ssl) {
      $handle = ftp_ssl_connect ($svr, $port);
    } else {
    
      $handle = ftp_connect ($svr, $port);
    }
    
    if ($handle)
      break;
  }
  
  if (!$handle)
    return  error ($cmd, "cannot connect to FTP server : $svr ($connect_retries attempts)");
  
  
  # log in
  
  debug_echo ($cmd, "logging in to FTP server : $svr (user = $usr) ...");
  
  $success = false;
  
  for ($j=0; $j<$login_retries; $j++) {
    
    if (ftp_login ($handle, $usr, $pwd)) {

      $success = true;
      break;
    }
  }
  
  if (!$success)
    return  error ($cmd, "cannot log in to FTP server : $svr (user = $usr) ($login_retries attempts)");

  
  # set pasv if required
  
  if ($pasv !== false) {
    
    
    # set to passive mode

    debug_echo ($cmd, "setting FTP connection to passive mode : $svr ...");

    $success = false;
        
    for ($i=0; $i<$pasv_retries; $i++) {
    
      if (ftp_pasv ($handle, true)) {
      
        $success = true;
        break;
      }
    }
    
    if (!$success)
      return  error ($cmd, "cannot set FTP connection to passive : $svr ($pasv_retries attempts)");
  }

  
  # create array of connections if required
  
  if (!array_key_exists ('ftp_conns', $GLOBALS) || !is_array ($GLOBALS['ftp_conns'])) {
    $GLOBALS['ftp_conns'] = array ();
  }
  
  
  # create conn
  
  $conn = array (
  
    'svr' => $svr,
    'usr' => $usr,
    'pwd' => $pwd,
    'handle' => $handle,
  );
  
  
  # save connection and return
  
  $GLOBALS['ftp_conns'][$conn_name] = $conn;
  
  debug_echo ($cmd, "connection to FTP server initiated : $svr");
  
  return  $conn;
}


function  ftp_get_conn (&$opts, $cmd) {


  # set prefix

  $prefix = 'ftp';

  
  # get svr opt

  $svr = get_opt ($prefix, $opts, 'svr');
  
  if (!check_opt_if_set_type ($cmd, $svr, 'svr', 'string'))
    return  false;

  
  # get usr opt
  
  $usr = get_opt ($prefix, $opts, 'usr');
  
  if (!check_opt_if_set_type ($cmd, $usr, 'usr', 'string'))
    return  false;
  
  
  # get pwd opt
  
  $pwd = get_opt ($prefix, $opts, 'pwd');
  
  if (!check_opt_if_set_type ($cmd, $pwd, 'pwd', 'string'))
    return  false;
    
    
  # check if svr,usr,pwd are set and create connection if so
  
  if ($svr && $usr && $pwd) {
    return  ftp_init ($opts, $cmd);
  }
    

  # get ftp conn opt
  
  $ftp_conn = get_opt ($prefix, $opts, 'ftp_conn');
    
  if (!check_opt_if_set_type ($cmd, $ftp_conn, 'ftp_conn', 'array'))
    return  false;
    
    
  # check conn is a real connection if set
    
  if ($ftp_conn && array_key_exists ('handle', $ftp_conn)) {
  
    $handle = $ftp_conn['handle'];
    
    if (is_resource ($handle) && get_resource_type ($handle) == 'FTP Buffer')
      return  $ftp_conn;
  }
  
    
  # get conn opt
  
  $conn_name = get_opt ($prefix, $opts, 'conn', 'default');
    
  if (!check_opt_set_type ($cmd, $conn_name, 'conn', 'string'))
    return  false;
    
  # TODO: check the server as well
  
  # check whether connection exists
      
  $ftp_conns = $GLOBALS['ftp_conns'];
    
  if (!array_key_exists ($conn_name, $ftp_conns))
    return  error ($cmd, "FTP connection '$conn_name' does not exist");
    
  
  # save to the passed opts
    
  $ftp_conn = $ftp_conns[$conn_name];
    
  $opts['ftp_conn'] = $ftp_conn;
    
  return  $ftp_conn;
}


function  ftp_get_file ($opts, $pipe = false, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'remote_file');
  
  
  # get execute opt
  
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);
  
  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
  
  
  # check if we should execute or not
  
  if (!$execute)
    return  true;
  
  
  # get remote file opt
  
  $remote_file = get_opt ($prefix, $opts, 'remote_file');
  
  if (!check_opt_set_type ($cmd, $remote_file, 'remote_file', 'string'))
    return  false;
     
  
  # get local file opt
  
  $local_file = get_opt ($prefix, $opts, 'local_file', $remote_file);
  
  if (!check_opt_set_type ($cmd, $local_file, 'local_file', 'string'))
    return  false;
  
  
  # get get retries opt
  
  $get_retries = get_opt_config_value ($prefix, $opts, 'get_retries', 3);
  
  if (!check_opt_set_type ($cmd, $get_retries, 'get_retries', 'integer'))
    return  false;
  
  
  # get download progress update opt
  
  $download_progress_update = get_opt_config_value ($prefix, $opts, 'download_progress_update', true);
  
  if (!check_opt_set_type ($cmd, $download_progress_update, 'download_progress_update', 'boolean'))
    return  false;
  
  
  # check to see if we shall re-use the file if it already exists

  if (is_file ($local_file)) {
  
    $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
    
    if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
      return  false;
  
  
    # get backup old downloaded files

    $backup_old_saved_files = get_opt_config_value ($prefix, $opts, 'backup_old_saved_files', true);
    
    if (!check_opt_set_type ($cmd, $backup_old_saved_files, 'backup_old_saved_files', 'boolean'))
      return  false;
    
    
    if ($use_saved_file) {
    
      debug_echo ($cmd, "using saved file instead of executing the FTP request : $local_file");
      
      return  array ('file' => $local_file);
      
    } elseif ($backup_old_saved_files && !backup_file ($local_file)) {
    
      return  error ($cmd, "could not backup previously downloaded file : $local_file");
    }
  }
  

  # setup connection

  $conn = ftp_get_conn ($opts, $cmd);
  
  if (!$conn)
    return  false;
  
  
  # get connection values
  
  $ftp_handle = $conn['handle'];
  $svr = $conn['svr'];
    
    
  # make the parent dir of local file
  
  if (!make_dir (dirname ($local_file), $opts, $cmd))
    return  false;
  
  
  # get the size of the remote file
  
  $size = ftp_size ($ftp_handle, $remote_file);
  
  if ($size == -1) {
  
    debug_echo ($cmd, "fetching file from FTP server : $remote_file => $local_file (unknown size) ...");
   
  } else {
  
    $size_str = human_filesize ($size);
  
    debug_echo ($cmd, "fetching file from FTP server : $remote_file => $local_file (size: $size_str) ...");
  }
  
  
  # get the file
  
  if ($download_progress_update) {
  
  
    # get the file with a progress monitor (a little slower)
    
    for ($j=0; $j<$get_retries; $j++) {
      
      $ret = ftp_nb_get ($ftp_handle, $local_file, $remote_file, FTP_BINARY);
      
      while ($ret == FTP_MOREDATA) {
        
        
        # get the size of the downloaded file (note: we need to use file handlers otherwise it doesn't work)
        
        $fh = fopen ($local_file, 'r');
        $stat = fstat ($fh);
        $dl_size = $stat['size'];
        fclose ($fh);
      
        
        # print progress
        
        download_progress ($size, $dl_size);
            
        
        # continue downloading
        
        $ret = ftp_nb_continue ($ftp_handle);
      }
      
      if ($ret == FTP_FINISHED) {
        return  array ('file' => $local_file);
      }
    }
  
  } else {
  

    # get local handle

    $local_file_handle = fopen ($local_file, 'w');

    if (!$local_file_handle)
      return  error ($cmd, "could not open local file : $local_file");

      
    # get the file without a progress monitor (a bit quicker)
    
    for ($j=0; $j<$get_retries; $j++) {
      
      if (@ftp_fget ($ftp_handle, $local_file_handle, $remote_file, FTP_BINARY)) {
        
        return  array ('file' => $local_file);
      }
    }
    
    fclose ($local_file_handle);
  }
  
  
  return  error ($cmd, "could not fetch file from FTP server : $remote_file");
}



function  ftp_get_files ($opts, $pipe = false, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe);
  

  # get execute opt
  
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);
  
  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
  
  
  # check if we should execute or not
  
  if (!$execute)
    return  true;
    
  
  # get files opt
  
  $files = get_opt ($prefix, $opts, 'files');
  
  if (!check_opt_set_type ($cmd, $files, 'files', 'array'))   # array_of_[string|string_array_1|string_array_2]
    return  false;  
  
  
  # setup connection

  $conn = ftp_get_conn ($opts, $cmd);
  
  if (!$conn)
    return  false;

  $svr = $conn['svr'];
  
  
  # download all the files
  
  $local_files = array ();
  $count = count ($files);
    
  debug_echo ($cmd, "downloading all files from FTP server ($count in total)");
  
  
  foreach ($files as $file_pair) {
  
  
    # get the remote and local file names
  
    if (is_string ($file_pair)) {
    
      $remote_file = $local_file = $file_pair;
      
    } elseif (is_array ($file_pair)) {
    
      # TODO: better error handling here
    
      $remote_file = $file_pair[0];
      
      if (!$remote_file) {
      
        return  error ($cmd, "remote file not set for FTP download");
      }
      
      $local_file = $file_pair[1];
      
      if (!$local_file)
        $local_file = $remote_file;
    
    } else {
    
      $type = gettype ($file_pair);
      
      return  error ($cmd, "file defined for FTP transfer is of type '$type'");
    }
  
  
    # download the file
    
    $opts['remote_file'] = $remote_file;
    $opts['local_file'] = $local_file;
    
    $r = ftp_get_file ($opts, null, $cmd);
    
    if ($r === false)
      return  false;
      
    $local_files[] = $local_file;
  }

  
  # return
  
  if ($pipe === false)
    return  $local_files;
  
  return  array ('files' => $local_files);
}


function  ftp_get_files_in_dir ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');
  
  
  # get execute opt
  
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);
  
  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
  
  
  # check if we should execute or not
  
  if (!$execute)
    return  true;
  

  # get dir opt
  
  $dir = get_opt ($prefix, $opts, 'dir');
  
  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
  
  
  # get local dir opt
  
  $local_dir = get_opt ($prefix, $opts, 'local_dir', $dir);
  
  if (!check_opt_set_type ($cmd, $local_dir, 'local_dir', 'string'))
    return  false;
    
  
  # setup connection

  $conn = ftp_get_conn ($opts, $cmd);
  
  if (!$conn)
    return  false;

  
  # clean and make dir
  
  if (!clean_dir ($local_dir, $opts, $cmd))
    return  false;
    
  if (!make_dir ($local_dir, $opts, $cmd))
    return  false;
  
  $opts['make_dir'] = false;
  
  
  # get list of files from remote server
  
  $files = ftp_scandir ($opts, false, $cmd);
  
  if (!$files)
    return  false;
  
  
  # build list of files
  
  $file_list = array ();
  
  foreach ($files as $file_name) {
  
    $remote_file = "$dir/$file_name";
    $local_file = "$local_dir/$file_name";
  
    $file_list[] = array ($remote_file, $local_file);
  }
  
  $opts['files'] = $file_list;
  
  
  # get all the files
  
  return  ftp_get_files ($opts, null, $cmd);
}


function  ftp_rawlist_2_nlist ($list) {

  // Note: this is used for ftp_scandir() if rawlist is used instead of nlist

  $newlist = array();
  reset ($list);

  while (list (,$row) = each ($list)) {
  
    $buf="";
    
    if ($row[0] == 'd' || $row[0] == '-') {
      $buf = substr ($row, 55);
      $newlist[]=$buf;
    }
  }

  return  $newlist;
}


function  ftp_scandir ($opts, $pipe = false, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';
  

  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'dir');
  
  
  # get execute opt
  
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);
  
  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
  
  
  # check if we should execute or not
  
  if (!$execute)
    return  true;
    

  # get remote dir opt
    
  $dir = get_opt ($prefix, $opts, 'dir');
  
  if (!check_opt_set_type ($cmd, $dir, 'dir', 'string'))
    return  false;
    
  
  # get search opt
  
  $search = get_opt ($prefix, $opts, 'search', '');
  
  if (!check_opt_set_type ($cmd, $search, 'search', 'string'))
    return  false;
  
  
  # get chdir retries
  
  $chdir_retries = get_opt_config_value ($prefix, $opts, 'chdir_retries', 3);
    
  if (!check_opt_set_type ($cmd, $chdir_retries, 'chdir_retries', 'integer'))
    return  false;
    
    
  # get scandir retries opt
  
  $scandir_retries = get_opt_config_value ($prefix, $opts, 'scandir_retries', 3);

  if (!check_opt_set_type ($cmd, $scandir_retries, 'scandir_retries', 'integer'))
    return  false;
    
  
  # setup connection

  $conn = ftp_get_conn ($opts, $cmd);
  
  if (!$conn)
    return  false;
  
  
  # get connection values
  
  $ftp_handle = $conn['handle'];
  $svr = $conn['svr'];
    
    
  # change to dir (note: we chdir because some server do not allow rawlist except in current working directory)
   
  debug_echo ($cmd, "scanning dir on FTP server : $dir ...");
  
  $success = false;
  
  for ($i=0; $i<$chdir_retries; $i++) {
  
    if (@ftp_chdir ($ftp_handle, $dir)) {
      $success = true;
      break;
    }
  }
  
  if (!$success)
    return  error ($cmd, "could not chdir to dir on FTP server : $dir ($chdir_retries attempts)");
  
  
  # build full search
  
  if ($dir == '/') {
    $full_search = "/$search";
    $path_offset = 1;
    
  } else {
    $full_search = "$dir/$search";
    $path_offset = strlen ($dir) + 1;
  }
  
  
  # try to get list
  
  for ($i=0; $i<$scandir_retries; $i++) {
  
    // Note: if rawlist is used then it's because a server does not accept nlist
  
    $list = @ftp_nlist ($ftp_handle, $full_search);
    
    if (!is_array ($list))
      break;
  }
  
  if (!$list)
    return  error ($cmd, "could not scan dir on FTP server : $dir ($scandir_retries attempts)");

    
  # convert rawlist
    
  //$list = ftp_rawlist_2_nlist ($list);
    
    
  # remove directory name
  
  $list = array_strip_offset_util ($list, $path_offset);
  
    
  # display list of files
  
  $count = count ($list);
  
  debug_echo ($cmd, "$count files found in dir on FTP server : $dir");
  
  debug_dump_list ($list, true);

  
  # return
    
  if ($pipe === false)
    return  $list;
    
  return  array ('files' => $list);
}


function  ftp_check_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');

  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
    
    
  # save the use download file opt and update
  
  $old_ftp_use_saved_file = get_config_value ('ftp_use_saved_file');
  
  set_config_value ('ftp_use_saved_file', $use_saved_file);
  set_config_value ('ftp_old_use_saved_file', $old_ftp_use_saved_file);
  
  
  # return if not using downloaded file
  
  if (!$use_saved_file) {
    return  array ('file' => null);
  }
  
  
  # get file opt
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
  
  
  # set the old ftp execute
  
  $old_ftp_execute = get_config_value ('ftp_execute');
  
  set_config_value ('ftp_old_execute', $old_ftp_execute);
  
    
  # check to see if the file exists already
  
  if (!file_exists ($file))
    return  array ('file' => null);
    
    
  # cancel ftp request execution
  
  debug_echo ($cmd, "using saved file instead of executing the FTP request : $file");
  
  set_config_value ('ftp_execute', false);
  
  
  # store file path and return
  
  set_config_value ('ftp_checked_saved_file', $file);
  
  return  array (
    'file' => $file,
    'response_file' => $file
  );
}


function  ftp_use_checked_saved_file ($opts, $pipe, $cmd = __FUNCTION__) {


  # set prefix

  $prefix = 'ftp';
  

  # merge opts

  $opts = merge_opts ($opts, $pipe, 'use_saved_file');
  
  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
  
  
  # reset use download file opt
  
  $old_use_saved_file = get_config_value ('ftp_old_use_saved_file');
  
  set_config_value ('ftp_use_saved_file', $old_use_saved_file);
  
  
  # check to see if should use downloaded file or not
  
  if (!$use_saved_file)
    return  $pipe;
  

  # restore ftp request execution config
  
  $ftp_execute = get_config_value ('ftp_old_execute');
  
  set_config_value ('ftp_execute', $ftp_execute);
  
  
  # get checked downloaded file opt
  
  $file = get_config_value ('ftp_checked_saved_file');
    
  if (!check_opt_if_set_type ($cmd, $file, 'ftp_checked_saved_file', 'string'))
    return  false;
    
    
  # if file does not exist just pipe
    
  if (!$file)
    return  $pipe;
    
  
  # reset config and return
  
  set_config_value ('ftp_checked_saved_file', null);
  
  
  return  array (
    'file' => $file,
    'response_file' => $file,
  );
}


?>