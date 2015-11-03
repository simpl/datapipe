<?php


# Note: These values can be changed here, but there are defaults that are hard-coded if the values are not set at global scope
# Note: The original values set here are the same as the hard-coded defaults


$default_config = array (

  'create_zip_message_type_on_zero_files'                       => 'warning',

  'curl_debug'                                                  => false,
  'curl_execute'                                                => true,
  'curl_request_retries'                                        => 3,
  
  'debug'                                                       => true,
  
  'file_backup_backup_file'                                     => true,
  'file_backup_suffix'                                          => '.bak.',
  
  'filter_data_secondary_data_key'                              => 'results_indexes',
  'filter_data_secondary_data_source'                           => 'search',
  'filter_data_secondary_data_type'                             => 'array_of_indexes',
  'filter_data_secondary_fields_key'                            => 'results_fields',
  'filter_data_type'                                            => 'join',
    
  'ftp_backup_old_saved_files'                                  => false,
  'ftp_chdir_retries'                                           => 3,
  'ftp_connect_retries'                                         => 3,
  'ftp_download_progress_update'                                => true,
  'ftp_execute'                                                 => true,
  'ftp_get_retries'                                             => 3,
  'ftp_login_retries'                                           => 3,
  'ftp_pasv'                                                    => true,
  'ftp_pasv_retries'                                            => 3,
  'ftp_scandir_retries'                                         => 3,
  'ftp_use_saved_file'                                          => false,

  'http_auto_redirect'                                          => true,
  'http_backup_old_saved_files'                                 => false,
  'http_debug'                                                  => false,
  'http_download_progress_update'                               => true,
  'http_execute'                                                => true,
  'http_max_response_body_size'                                 => 512*1024*1024,
  'http_parse_response_form_post_args'                          => false,
  'http_request_method'                                         => 'get',
  'http_use_saved_file'                                         => false,

  'list_indent'                                                 => 2,
  
  'load_data_from_csv_file_delimiter'                           => ',',
  'load_data_from_csv_file_limit'                               => 0,
  'load_data_from_csv_file_offset'                              => 0,
  'load_data_from_csv_file_trim'                                => true,
  
  'process_data_debug'                                          => false,
  'process_data_primary_key'                                    => 'guid',
  'process_data_type'                                           => 'outer',   # inner|outer 
    
  'result_set_as_key_index_index'                               => 0,
  
  'split_file_on_cols_trim'                                     => true,

  'suredone_bulk_upload_csv_file_name'                          => 'data.csv',
  'suredone_bulk_upload_index_padding'                          => 2,
  'suredone_bulk_upload_include_data_with_missing_media'        => true,
  'suredone_bulk_upload_ignored_data_file_name'                 => 'ignored-data.csv',
  'suredone_bulk_upload_max_lines_per_page'                     => 1000,
  'suredone_bulk_upload_max_media_per_page'                     => 5000,
  'suredone_bulk_upload_max_media_total_size_per_page'          => 250*1024*1024,  # 250MB
  'suredone_bulk_upload_max_pages'                              => 0,
  'suredone_bulk_upload_media_dir'                              => 'media',
  'suredone_bulk_upload_zip_file_name'                          => 'media.zip',
  
  'suredone_media_file_fields'                                  => array (                                     
                                                                  'mediafile',
                                                                  'mediafile1',
                                                                  'mediafile2', 
                                                                  'mediafile3', 
                                                                  'mediafile4', 
                                                                  'mediafile5', 
                                                                  'mediafile6', 
                                                                  'mediafile7', 
                                                                  'mediafile8', 
                                                                  'mediafile9', 
                                                                  'mediafile10'
                                                                ),
  'suredone_search_fields'                                      => '*',
  'suredone_search_max_pages'                                   => 0,
  'suredone_search_max_results'                                 => 0,
  'suredone_search_message_type_on_no_field'                    => 'error',
  'suredone_search_save_indexes'                                => 'guid',
  'suredone_search_query'                                       => '',
  'suredone_search_type'                                        => 'items',
  'suredone_search_use_saved_file'                              => true,
  'suredone_search_use_saved_pages'                             => false,
  'suredone_user'                                               => 'maws',
  'suredone_token' => '4818025C2127A192B3D359971D522C2635DDA375516B4C8CE797FFB8DF1885DFE03061EC29FA62971GUV9IM1YHPZGPAZS2DAGKWFB4W0',
  
  'syslog_on_failure'                                           => true,
  'syslog_on_success'                                           => false,
  'syslog_failure_priority'                                     => LOG_ERR,
  'syslog_success_priority'                                     => LOG_INFO,
  
  'unzip_perms'                                                 => 0755,
  
  'xml_parse_document_doc_type'                                 => 'xml',
  'xml_parse_document_element_type'                             => 'elements',
  
  'yaml_indent'                                                 => 2,
);


# set config values not already set

foreach ($default_config as $name => $value) {

  if (!array_key_exists ($name, $GLOBALS)) {
    
    $GLOBALS[$name] = $value;
  }
}


?>