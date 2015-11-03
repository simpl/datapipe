<?php


$curl_info = array (

  'options' => array (
  
    'all' => array (
    
      'header_out'
    ),
  ),
);


$curl_default_ports = array (

  'ftp'   => 21,
  'ftps'  => 990,
  'http'  => 80,
  'https' => 443,
  'sftp'  => 22,
);


$curl_options = array (

  # types
  
  # TODO: pre-generate the defaults / options when enum'ed

  /*
  
  b         boolean
  f_r       file (read)
  f_w       file (write)
  fn        function (only settable internally)
  i         integer
  i_bm      integer bitmask
  i_ma      integer (with max)
  i_mi_ma   integer (with min and max)
  s         string
  s_d_r     directory path (read)
  s_d_w     directory path (write)
  s_f_r     file path (read)
  s_f_w     file path (write)
  s_u       upper-case string (auto conversion)

  */

  'defaults'=> array (
  
    'all' => array (
    
      #'safe_upload'               =>  true,     # true to disable @ prefix for uploading files in CURLOPT_POSTFIELDS (default: 5.5+ false*, 5.6+ true)
      #'ssl_verifypeer'            =>  false,    # false to not verify the peer's certificate (default: true)
      'returntransfer'            =>  true,     # result is returned as string
      #'transfertext'              =>  true,     # returns the input as a string
      'connecttimeout'            =>  30,       # 0 to wait indefinitely
    ),
  
    'ftp' => array (
      'protocols'                 =>  (CURLPROTO_FTP | CURLPROTO_FTPS | CURLPROTO_SCP | CURLPROTO_SFTP | CURLPROTO_TFTP | CURLPROTO_FILE),    # might want to move into diff't functions   
      #'ftpport'                   => '-',
    ),

    'ftps' => array (
      'protocols'                 =>  (CURLPROTO_FTP | CURLPROTO_FTPS | CURLPROTO_SCP | CURLPROTO_SFTP | CURLPROTO_TFTP | CURLPROTO_FILE),    # might want to move into diff't functions   
      #'ftpport'                   => '-',
      #'sslversion'                => 4,
      #'ssl_verifyhost'            => false,
    ),
    
    'http' => array (
    
      #'cookiefile'                => '/tmp/suredone-plugin-cookies',
      #'cookiejar'                 => '/tmp/suredone-plugin-cookies',
      'followlocation'            =>  true,     # follow Location headers (recursively) - unlimited unless CURLOPT_MAXREDIRS is set
      'header'                    =>  true,
      'maxredirs'                 =>  10,
      'protocols'                 =>  (CURLPROTO_HTTP | CURLPROTO_HTTPS | CURLPROTO_FILE),
      'transfertext'              =>  true,     # returns the input as a string
    ),
    
    'https' => array (
    
      #'cookiefile'                => '/tmp/suredone-plugin-cookies',
      #'cookiejar'                 => '/tmp/suredone-plugin-cookies',
      'followlocation'            =>  true,     # follow Location headers (recursively) - unlimited unless CURLOPT_MAXREDIRS is set
      'header'                    =>  true,
      'maxredirs'                 =>  10,
      'protocols'                 =>  (CURLPROTO_HTTP | CURLPROTO_HTTPS | CURLPROTO_FILE),
    ),

    'imap' => array (
    
      'protocols'                 => (CURLPROTO_FILE),
    ),
    
    'ldap' => array (
    
      'protocols'                 => (CURLPROTO_LDAP | CURLPROTO_LDAPS | CURLPROTO_FILE),
    ),

    'pop3' => array (
    
      'protocols'                 => (CURLPROTO_FILE),
    ),
    
    'smtp' => array (
    
      'protocols'                 => (CURLPROTO_FILE),
    ),
    
    'telnet' => array (
      'protocols'                 => (CURLPROTO_TELNET | CURLPROTO_FILE),
    ),
  ),

  'options' => array (
  
    'all' => array (

      'cainfo'                    =>  's_f_r',  # file that contains CA info to verify peer certificates
      'capath'                    =>  's_d_r',  # dir containing multiple CA certs - used with 'ssl_verifypeer'
      'certinfo'                  =>  'b',      # output SSL cert info to stderr on secure transfers (requires 'verbose' to be on to have effect)
      'connect_only'              =>  'b',      # perform proxy authentication / connection setup, but no transfer
      'crlf'                      =>  'b',      # \n => \n\r
      'customrequest'             =>  's',      # TODO: change to the different options available for each protocols
      'dns_use_global_cache'      =>  'b',      # default 
      'edgsocket'                 =>  's_f_w',  # like CURLOPT_RANDOM_FILE, except a filename to an Entropy Gathering Daemon socket
      'file'                      =>  'f_w',    # file that the transfer should be written to. The default is STDOUT (the browser window)
      'filetime'                  =>  'b',      # attempt to get modification time of the remote file (retrieve using curl_getinfo (CURLINFO_FILETIME)
      'forbidreuse'               =>  'b',      # force connection to close when finished processing and not be pooled
      'fresh_connect'             =>  'b',      # force a new connection instead of a cached one
      'header'                    =>  'b',      # include the header in the output
      'headerfunction'            =>  'fn',     # [bytes written] (cURL resource, header to write)
      'httpproxytunnel'           =>  'b',      # tunnel through HTTP proxy
      'infile'                    =>  'f_r',    # the file that the transfer should be read from when uploading
      'infilesize'                =>  'i',      # expected file size of file to send (but will not stop sending more - depends on CURLOPT_READFUNCTION
      'ipresolve'                 =>  array (   # how to resolve IP addresses
                                        'i',
                                        CURL_IPRESOLVE_WHATEVER,
                                        CURL_IPRESOLVE_V4, 
                                        CURL_IPRESOLVE_V6,
                                      ),
      'keypasswd'                 =>  's',      # password required to use the CURLOPT_SSLKEY or CURLOPT_SSH_PRIVATE_KEYFILE private key
      'krb4level'                 =>  array (   # kerberos 4 security leve (lowest->highest: clear, safe, confidential, private [default])
                                        's',
                                        'clear', 
                                        'safe', 
                                        'confidential', 
                                        'private',
                                      ),
      'low_speed_limit'           =>  'i',      # min bytes per second before terminate***
      'low_speed_time'            =>  'i',      # no. of secs that transfer should be below low_speed_limit before terminating
      'max_recv_speed_large'      =>  'i',      # default unlimited
      'max_send_speed_large'      =>  'i',      # default unlimited
      'maxconnects'               =>  array (   # max no. of persistent connections
                                        'i_ma',
                                        128,
                                      ),
      'netrc'                     =>  'b',      # scan ~/.netrc to find username and password for remote site
      'noprogress'                =>  'b',      # disable progress monitor [default: true]
      'nosignal'                  =>  'b',      # ignore signals sent to process (default in multi-threaded SAPI's)
      'passwdfunction'            =>  'fn',     # [password] (cURL resouce, prompt, max_length)
      'port'                      =>  array (
                                        'i_mi_ma',
                                        1,
                                        65535,
                                      ),
      'progressfunction'          =>  'fn',     # [non-zero to abort] (cURL resource, download size, downloaded so far, upload size, uploaded so far) 
      'random_file'               =>  's_f_w',  # file for SSL random number generator
      'readfunction'              =>  'fn',     # [string <= max, '' for EOF] (cURL resource, read stream, max data to be read)

      'resume_from'               =>  'i',      # the offset, in bytes, to resume a transfer from
      'ssl_cipher_list'           =>  's',      # list of ciphers to use (e.g. RC4-SHA and TLSv1)
      'ssl_verifypeer'            =>  'b',      # false to not verify the peer's certificate (default: true)
      'ssl_version'               =>  array (   # best left to default
                                        'i',
                                        CURL_SSLVERSION_DEFAULT,    # 0 
                                        CURL_SSLVERSION_TLSv1,      # 1
                                        #CURL_SSLVERSION_SSLv2,     # 2 (dangerous)
                                        #CURL_SSLVERSION_SSLv3,     # 3 (dangerous)
                                        #CURL_SSLVERSION_TLSv1_0,   # 4 (unsupported)
                                        #CURL_SSLVERSION_TLSv1_1,   # 5 (unsupported)
                                        #CURL_SSLVERSION_TLSv1_2,   # 6 (unsupported)
                                      ),
      'sslcert'                   =>  's_f_r',  # SSL cert file (PEM format)
      'sslcertpasswd'             =>  's',      # password for 'sslcert'
      'ssscerttype'               =>  array (
                                        's',
                                         'PEM',  # (default)
                                         'DER', 
                                         'ENG',
                                      ),
      'sslengine'                 =>  's',      # identifier for the crypto engine of the private SSL key specified in CURLOPT_SSLKEY
      'sslenginedefault'          =>  's',      # identifier for the crypto engine used for asymmetric crypto operations
      'sslkey'                    =>  's',      # name of a file containing a private SSL key
      'sslkeypasswd'              =>  's',      # 
      'sslkeytype'                =>  array (
                                        's',
                                        'PEM',  # (default)
                                        'DER',
                                        'ENG',
                                      ),
      'stderr'                    =>  'f_w',    # an alternative location to output errors to instead of STDERR
      'tcp_nodelay'               =>  'b',      # pass a long specifying whether TCP_NODELAY should be set (1 = set, 0 = clear [default])
      'timecondition'             =>  array (   # how 'timevalue' is dealt with
                                        'i',
                                        CURL_TIMECOND_IFMODSINCE,   # only return if modified since (default)
                                        CURL_TIMECOND_IFUNMODSINCE,
                                      ),
      'timeout'                   =>  'i',      # in seconds
      'timevalue'                 =>  'i',      # wrt 'timecondition'
      'transfertext'              =>  'b',      # true to use ASCII mode for FTP, for LDAP, retrieves data in plain text instead of HTML
      'unrestricted_auth'         =>  'b',      # keep sending username / password when following Locations, even when the hostname changes
      'upload'                    =>  'b',      # prepare for an upload
      'url'                       =>  's',
      'userpwd'                   =>  's',      # [username]:[password]
      'verbose'                   =>  'b',      # true to output verbose info to stderr (of file specified using stderr)
      'writefunction'             =>  'fn',     # [bytes written] (cURL resource, data to be written)
    ),
    
    'ftp' => array (
    
      'ftp_create_missing_dirs'   =>  'b',      # create missing dirs when FTP op encounters a path that doesn't exist
      'ftp_use_eprt'              =>  'b',      # false to disable using EPRT and LPRT (i.e. PORT only) for FTP downloads (i.e. use active)
      'ftp_use_epsv'              =>  'b',      # false to disable EPSV (i.e. trying EPSV before PASV) 
      'ftpappend'                 =>  'b',      # try to append to the remote file instead of overwriting it
      'ftplistonly'               =>  'b',      # true to only list the contents of an FTP directory
      'ftpport'                   =>  's',      # address for server to connect to (IP / host / socket / - [default system IP)
      'ftpslauth'                 =>  array (
                                        'i',
                                        CURLFTPAUTH_SSL,
                                        CURLFTPAUTH_TLS,
                                        CURLFTPAUTH_DEFAULT,
                                      ),
      'postquote'                 =>  'a',      # an array of FTP commands to execute on the server after the FTP request has been performed
      'quote'                     =>  'a',      # an array of FTP commands to execute on the server prior to the FTP request
      'writeheader'               =>  'f_w',    # file that the header part of the transfer is written to
    ),
    
    'http' => array (
    
      'autoreferer'               =>  'b',      # automatically set Referer when follows Location
      'cookie'                    =>  's',      # cookie header, with multiple separared like: "fruit=apple; colour=red"
      'cookiefile'                =>  's_f_r',  # file containing cookies
      'cookiejar'                 =>  's_f_w',  # file to save cookies to until close connection
      'cookiesession'             =>  'b',      # mark as a new cookie session (by default loads all cookies)
      'customrequest'             =>  array (
                                        's_u',
                                        'connect',
                                        'delete',
                                        'get',
                                        'head',
                                        'options',
                                        'patch',
                                        'post',
                                        'put',
                                        #'TRACE',   # (security considerations)
                                      ),
      'encoding'                  =>  array (
                                        's',
                                        '',         # all
                                        'deflate', 
                                        'gzip',
                                        'identity',
                                      ),
      'failonerror'               =>  'b',      # HTTP code >= 400  =>  fail verbosely
      'followlocation'            =>  'b',      # follow Location headers (recursively) - unlimited unless CURLOPT_MAXREDIRS is set
      'httpget'                   =>  'b',      # true to set HTTP GET (default, only necessary if changed
      'httpheader'                =>  'a',      # e.g. array('Content-type: text/plain', 'Content-length: 100') 
      'http_version'              =>  array (
                                        'i',
                                        CURL_HTTP_VERSION_1_0,
                                        CURL_HTTP_VERSION_1_1,
                                      ),
      'http200aliases'            =>  'a',      # no errors on these
      'httpauth'                  =>  array (
                                        'i_bm',
                                        CURLAUTH_BASIC,
                                        CURLAUTH_DIGEST,
                                        CURLAUTH_DIGEST, 
                                        CURLAUTH_GSSNEGOTIATE, 
                                        CURLAUTH_NTLM, 
                                        CURLAUTH_ANY,           # CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM
                                        CURLAUTH_ANYSAFE,       # CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM
                                      ),
      'maxredirs'                 =>  array (
                                        'i_ma',
                                        20,
                                      ),
      'nobody'                    =>  'b',      # exclude body from request (request method set to HEAD), true does not change back to GET
      'post'                      =>  'b',      # set to HTTP POST (application/x-www-form-urlencoded)
      'postfields'                =>  's|a',    # string: application/x-www-form-urlencoded, array: multipart/form-data)
      'postredir'                 =>  array (   # bitmask of HTTP codes that should follow if Location on HTTP POST
                                        'i_bm',
                                        1,      # 301  Moved Permanently
                                        2,      # 302  Found
                                        4,      # 303  See Other
                                      ),
      'proxy'                     =>  's',      # http proxy
      'proxyauth'                 =>  array (
                                        'i_bm',
                                        CURLAUTH_BASIC,
                                        CURLAUTH_NTLM,
                                      ),
      'proxyport'                 =>  array (
                                        'i_mi_ma',
                                        1,
                                        65535,
                                      ),
      'proxytype'                 =>  array (
                                        'i',
                                        CURLPROXY_HTTP,
                                        CURLPROXY_SOCKS5,
                                      ),
      'proxyuserpwd'              =>  's',      # [username]:[password] for proxy
      'put'                       =>  'b',      # put file (use CURLOPT_INFILE and CURLOPT_INFILESIZE)
      'referer'                   =>  's',      # Refefer header
      'useragent'                 =>  's',      # User-Agent header
    
    ),
    
    'scp|sftp' => array (
    
      'ssh_auth_types'            =>  array (
                                      'i_bm',
                                      CURLSSH_AUTH_PUBLICKEY, 
                                      CURLSSH_AUTH_PASSWORD, 
                                      CURLSSH_AUTH_HOST, 
                                      CURLSSH_AUTH_KEYBOARD,
                                      CURLSSH_AUTH_ANY,
                                      ),
      'ssh_host_public_key_md5'   =>  's',
      'ssh_public_keyfile'        =>  's_f_r',  # defaults to $HOME/.ssh/id_dsa.pub / id_dsa.pub in local dir
      'ssh_private_keyfile'       =>  's_f_r',  # defaults to $HOME/.ssh/id_dsa / id_dsa in current dir
    ),
  ),
);

/*
Usage: curl [options...] <url>
Options: (H) means HTTP/HTTPS only, (F) means FTP only
     --anyauth       Pick "any" authentication method (H)
 -a, --append        Append to target file when uploading (F/SFTP)
     --basic         Use HTTP Basic Authentication (H)
     --cacert FILE   CA certificate to verify peer against (SSL)
     --capath DIR    CA directory to verify peer against (SSL)
 -E, --cert CERT[:PASSWD] Client certificate file and password (SSL)
     --cert-type TYPE Certificate file type (DER/PEM/ENG) (SSL)
     --ciphers LIST  SSL ciphers to use (SSL)
     --compressed    Request compressed response (using deflate or gzip)
 -K, --config FILE   Specify which config file to read
     --connect-timeout SECONDS  Maximum time allowed for connection
 -C, --continue-at OFFSET  Resumed transfer offset
 -b, --cookie STRING/FILE  String or file to read cookies from (H)
 -c, --cookie-jar FILE  Write cookies to this file after operation (H)
     --create-dirs   Create necessary local directory hierarchy
     --crlf          Convert LF to CRLF in upload
     --crlfile FILE  Get a CRL list in PEM format from the given file
 -d, --data DATA     HTTP POST data (H)
     --data-ascii DATA  HTTP POST ASCII data (H)
     --data-binary DATA  HTTP POST binary data (H)
     --data-urlencode DATA  HTTP POST data url encoded (H)
     --delegation STRING GSS-API delegation permission
     --digest        Use HTTP Digest Authentication (H)
     --disable-eprt  Inhibit using EPRT or LPRT (F)
     --disable-epsv  Inhibit using EPSV (F)
     --dns-servers    DNS server addrs to use: 1.1.1.1;2.2.2.2
     --dns-interface  Interface to use for DNS requests
     --dns-ipv4-addr  IPv4 address to use for DNS requests, dot notation
     --dns-ipv6-addr  IPv6 address to use for DNS requests, dot notation
 -D, --dump-header FILE  Write the headers to this file
     --egd-file FILE  EGD socket path for random data (SSL)
     --engine ENGINE  Crypto engine (SSL). "--engine list" for list
 -f, --fail          Fail silently (no output at all) on HTTP errors (H)
 -F, --form CONTENT  Specify HTTP multipart POST data (H)
     --form-string STRING  Specify HTTP multipart POST data (H)
     --ftp-account DATA  Account data string (F)
     --ftp-alternative-to-user COMMAND  String to replace "USER [name]" (F)
     --ftp-create-dirs  Create the remote dirs if not present (F)
     --ftp-method [MULTICWD/NOCWD/SINGLECWD] Control CWD usage (F)
     --ftp-pasv      Use PASV/EPSV instead of PORT (F)
 -P, --ftp-port ADR  Use PORT with given address instead of PASV (F)
     --ftp-skip-pasv-ip Skip the IP address for PASV (F)
     --ftp-pret      Send PRET before PASV (for drftpd) (F)
     --ftp-ssl-ccc   Send CCC after authenticating (F)
     --ftp-ssl-ccc-mode ACTIVE/PASSIVE  Set CCC mode (F)
     --ftp-ssl-control Require SSL/TLS for ftp login, clear for transfer (F)
 -G, --get           Send the -d data with a HTTP GET (H)
 -g, --globoff       Disable URL sequences and ranges using {} and []
 -H, --header LINE   Custom header to pass to server (H)
 -I, --head          Show document info only
 -h, --help          This help text
     --hostpubmd5 MD5  Hex encoded MD5 string of the host public key. (SSH)
 -0, --http1.0       Use HTTP 1.0 (H)
     --http1.1       Use HTTP 1.1 (H)
     --http2.0       Use HTTP 2.0 (H)
     --ignore-content-length  Ignore the HTTP Content-Length header
 -i, --include       Include protocol headers in the output (H/F)
 -k, --insecure      Allow connections to SSL sites without certs (H)
     --interface INTERFACE  Specify network interface/address to use
 -4, --ipv4          Resolve name to IPv4 address
 -6, --ipv6          Resolve name to IPv6 address
 -j, --junk-session-cookies Ignore session cookies read from file (H)
     --keepalive-time SECONDS  Interval between keepalive probes
     --key KEY       Private key file name (SSL/SSH)
     --key-type TYPE Private key file type (DER/PEM/ENG) (SSL)
     --krb LEVEL     Enable Kerberos with specified security level (F)
     --libcurl FILE  Dump libcurl equivalent code of this command line
     --limit-rate RATE  Limit transfer speed to this rate
 -l, --list-only     List only mode (F/POP3)
     --local-port RANGE  Force use of these local port numbers
 -L, --location      Follow redirects (H)
     --location-trusted like --location and send auth to other hosts (H)
 -M, --manual        Display the full manual
     --mail-from FROM  Mail from this address (SMTP)
     --mail-rcpt TO  Mail to this/these addresses (SMTP)
     --mail-auth AUTH  Originator address of the original email (SMTP)
     --max-filesize BYTES  Maximum file size to download (H/F)
     --max-redirs NUM  Maximum number of redirects allowed (H)
 -m, --max-time SECONDS  Maximum time allowed for the transfer
     --metalink      Process given URLs as metalink XML file
     --negotiate     Use HTTP Negotiate Authentication (H)
 -n, --netrc         Must read .netrc for user name and password
     --netrc-optional Use either .netrc or URL; overrides -n
     --netrc-file FILE  Set up the netrc filename to use
 -N, --no-buffer     Disable buffering of the output stream
     --no-keepalive  Disable keepalive use on the connection
     --no-sessionid  Disable SSL session-ID reusing (SSL)
     --noproxy       List of hosts which do not use proxy
     --ntlm          Use HTTP NTLM authentication (H)
     --oauth2-bearer TOKEN  OAuth 2 Bearer Token (IMAP, POP3, SMTP)
 -o, --output FILE   Write output to <file> instead of stdout
     --pass PASS     Pass phrase for the private key (SSL/SSH)
     --post301       Do not switch to GET after following a 301 redirect (H)
     --post302       Do not switch to GET after following a 302 redirect (H)
     --post303       Do not switch to GET after following a 303 redirect (H)
 -#, --progress-bar  Display transfer progress as a progress bar
     --proto PROTOCOLS  Enable/disable specified protocols
     --proto-redir PROTOCOLS  Enable/disable specified protocols on redirect
 -x, --proxy [PROTOCOL://]HOST[:PORT] Use proxy on given port
     --proxy-anyauth Pick "any" proxy authentication method (H)
     --proxy-basic   Use Basic authentication on the proxy (H)
     --proxy-digest  Use Digest authentication on the proxy (H)
     --proxy-negotiate Use Negotiate authentication on the proxy (H)
     --proxy-ntlm    Use NTLM authentication on the proxy (H)
 -U, --proxy-user USER[:PASSWORD]  Proxy user and password
     --proxy1.0 HOST[:PORT]  Use HTTP/1.0 proxy on given port
 -p, --proxytunnel   Operate through a HTTP proxy tunnel (using CONNECT)
     --pubkey KEY    Public key file name (SSH)
 -Q, --quote CMD     Send command(s) to server before transfer (F/SFTP)
     --random-file FILE  File for reading random data from (SSL)
 -r, --range RANGE   Retrieve only the bytes within a range
     --raw           Do HTTP "raw", without any transfer decoding (H)
 -e, --referer       Referer URL (H)
 -J, --remote-header-name Use the header-provided filename (H)
 -O, --remote-name   Write output to a file named as the remote file
     --remote-name-all Use the remote file name for all URLs
 -R, --remote-time   Set the remote file's time on the local output
 -X, --request COMMAND  Specify request command to use
     --resolve HOST:PORT:ADDRESS  Force resolve of HOST:PORT to ADDRESS
     --retry NUM   Retry request NUM times if transient problems occur
     --retry-delay SECONDS When retrying, wait this many seconds between each
     --retry-max-time SECONDS  Retry only within this period
     --sasl-ir       Enable initial response in SASL authentication
 -S, --show-error    Show error. With -s, make curl show errors when they occur
 -s, --silent        Silent mode. Don't output anything
     --socks4 HOST[:PORT]  SOCKS4 proxy on given host + port
     --socks4a HOST[:PORT]  SOCKS4a proxy on given host + port
     --socks5 HOST[:PORT]  SOCKS5 proxy on given host + port
     --socks5-hostname HOST[:PORT] SOCKS5 proxy, pass host name to proxy
     --socks5-gssapi-service NAME  SOCKS5 proxy service name for gssapi
     --socks5-gssapi-nec  Compatibility with NEC SOCKS5 server
 -Y, --speed-limit RATE  Stop transfers below speed-limit for 'speed-time' secs
 -y, --speed-time SECONDS  Time for trig speed-limit abort. Defaults to 30
     --ssl           Try SSL/TLS (FTP, IMAP, POP3, SMTP)
     --ssl-reqd      Require SSL/TLS (FTP, IMAP, POP3, SMTP)
 -2, --sslv2         Use SSLv2 (SSL)
 -3, --sslv3         Use SSLv3 (SSL)
     --ssl-allow-beast Allow security flaw to improve interop (SSL)
     --stderr FILE   Where to redirect stderr. - means stdout
     --tcp-nodelay   Use the TCP_NODELAY option
 -t, --telnet-option OPT=VAL  Set telnet option
     --tftp-blksize VALUE  Set TFTP BLKSIZE option (must be >512)
 -z, --time-cond TIME  Transfer based on a time condition
 -1, --tlsv1         Use TLSv1 (SSL)
     --trace FILE    Write a debug trace to the given file
     --trace-ascii FILE  Like --trace but without the hex output
     --trace-time    Add time stamps to trace/verbose output
     --tr-encoding   Request compressed transfer encoding (H)
 -T, --upload-file FILE  Transfer FILE to destination
     --url URL       URL to work with
 -B, --use-ascii     Use ASCII/text transfer
 -u, --user USER[:PASSWORD][;OPTIONS]  Server user, password and login options
     --tlsuser USER  TLS username
     --tlspassword STRING TLS password
     --tlsauthtype STRING  TLS authentication type (default SRP)
 -A, --user-agent STRING  User-Agent to send to server (H)
 -v, --verbose       Make the operation more talkative
 -V, --version       Show version number and quit
 -w, --write-out FORMAT  What to output after completion
     --xattr        Store metadata in extended file attributes
 -q                 If used as the first parameter disables .curlrc
*/
  
  
function  curl ($opts, $pipe, $cmd = __FUNCTION__, $default_protocol = null) {
 
 
  # set up internal (which by default comes from there being a default protocol)
 
  if ($default_protocol) {
  
    $internal = true;
  
  } else { 
  
    $internal = false;
  }
 

  # merge the opts
  
  $opts = merge_opts ($opts, $pipe);

  
  # get prefix opt
 
  $prefix = get_opt ('curl', $opts, 'prefix', 'curl');

  if (!check_opt_set_type ($cmd, $prefix, 'prefix', 'string'))
    return  false;
    
  
  # get execute opt
 
  $execute = get_opt_config_value ($prefix, $opts, 'execute', true);

  if (!check_opt_set_type ($cmd, $execute, 'execute', 'boolean'))
    return  false;
    
    
  # check if we should execute or not
  
  if (!$execute) {
  
    debug_echo ($cmd, "not executing $prefix request");
  
    return;
  }
  
  
  # get debug opt

  $debug = get_opt_config_value ($prefix, $opts, 'debug', false);
  
  if (!check_opt_set_type ($cmd, $debug, 'debug', 'boolean'))
    return  false;
  
  
  ## saved files ##
  
  # get save_to_file opt
 
  $save_to_file = get_opt_config_value ($prefix, $opts, 'save_to_file');

  if (!check_opt_if_set_type ($cmd, $save_to_file, 'save_to_file', 'string'))
    return  false;
  
  
  # get use saved file opt

  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', false);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
  
  
  # get backup old saved files

  $backup_old_saved_files = get_opt_config_value ($prefix, $opts, 'backup_old_saved_files', false);
  
  if (!check_opt_set_type ($cmd, $backup_old_saved_files, 'backup_old_saved_files', 'boolean'))
    return  false;
  
  
  # check if file exists and back up if necessary

  if (file_exists ($save_to_file) && $use_saved_file) {
    
    debug_echo ($cmd, "using saved file instead of executing request : $save_to_file");
    
    return  array (
      'file'          => $save_to_file,
      'response_file' => $save_to_file,
    );
  }

  
  # get curlopts opt

  $curlopts = get_opt ($prefix, $opts, 'curlopts');
  
  if (!check_opt_if_set_type ($cmd, $curlopts, 'curlopts', 'array'))
    return  false;
  
  if (is_array ($curlopts)) {
  
    $curlopts = &$opts['curlopts'];
  
  } else {
  
    $curlopts = array ();
    $opts['curlopts'] = &$curlopts;
  }

  
  # get finalize_curlopts opt

  $finalize_curlopts = get_opt ($prefix, $opts, 'finalize_curlopts');
  
  if (!check_opt_if_set_type ($cmd, $finalize_curlopts, 'finalize_curlopts', 'array'))
    return  false;
  
  
  # set up response
  
  $res = array ();
  

  # call pre functions if internal and set
  
  if ($internal) {

  
    # get pre functions opt
  
    $init_functions = get_opt ($prefix, $opts, 'init_functions');
  
    if (!check_opt_if_set_type ($cmd, $init_functions, 'init_functions', 'array_of_strings'))
      return  false;

  
    # get pre exec functions opt
  
    $pre_exec_functions = get_opt ($prefix, $opts, 'pre_exec_functions');
  
    if (!check_opt_if_set_type ($cmd, $pre_exec_functions, 'pre_exec_functions', 'array_of_strings'))
      return  false;
  
  
    # get post exec functions opt

    $post_exec_functions = get_opt ($prefix, $opts, 'post_exec_functions');
    
    if (!check_opt_if_set_type ($cmd, $post_exec_functions, 'post_exec_functions', 'array_of_strings'))
      return  false;
    

    # get finalize functions opt

    $finalize_functions = get_opt ($prefix, $opts, 'finalize_functions');
    
    if (!check_opt_if_set_type ($cmd, $finalize_functions, 'finalize_functions', 'array_of_strings'))
      return  false;

    
    # set array of arrays to pass to hooked functions
    
    $func_args = array (
    
      'curlopts'  => &$curlopts,
      'opts'      => &$opts,
      'res'       => &$res,
    );
      
  
    # call the pre functions

    if ($init_functions) {
  
      foreach ($init_functions as $function) {

        $r = call_user_func ($function, $prefix, $func_args, $cmd, $debug);
        
        if ($r === false)
          return  false;
      }
    }
  }

 
  # get download progress update opt
  
  $download_progress_update = get_opt_config_value ($prefix, $opts, 'download_progress_update', true);
  
  if (!check_opt_set_type ($cmd, $download_progress_update, 'download_progress_update', 'boolean'))
    return  false;

  
  # add download process update to curlopts if necessary
  
  if ($download_progress_update) {
  
    $curlopts['noprogress'] = false;
  
    if ($curlopts['progressfunction'] = 'curl_progress_function');
    
  } else {
  
    $curlopts['noprogress'] = true;
  }
  
    
  # get url opt
    
  do {
    
    # get url opt (from curlopts)

    $url = get_opt ($prefix, $curlopts, 'url');
    
    if (!check_opt_if_set_type ($cmd, $url, 'url', 'string'))
      return  false;
    
    if ($url)
      break;
  
  
    # get url opt (from opts)
    
    $url = get_opt ($prefix, $opts, 'url');
  
    if (!check_opt_if_set_type ($cmd, $url, 'url', 'string'))
      return  false;
      
    if ($url)
      break;
      
      
    # get server opt
    
    $tries = array ('server', 'svr');
    
    foreach ($tries as $try) {
    
      $server = get_opt ($prefix, $opts, $try);
    
      if (!check_opt_if_set_type ($cmd, $server, $try, 'string'))
        return  false;
      
      if ($server)
        break;
    }
      
    
    # get path opt
    
    $path = get_opt ($prefix, $opts, 'path');
    
    if (!check_opt_if_set_type ($cmd, $path, 'path', 'string'))
      return  false;
    
    
    # build path from dir / file if necessary
    
    if (!$path) {
    
    
      # get dir opt
    
      $dir = get_opt ($prefix, $opts, 'dir');
      
      if (!check_opt_if_set_type ($cmd, $dir, 'dir', 'string'))
        return  false;
    
    
      # get file opt
    
      $file = get_opt ($prefix, $opts, 'file');
      
      if (!check_opt_if_set_type ($cmd, $file, 'file', 'string'))
        return  false;
    
    
      # build path from dir/file
      
      $path = '';
      
      if ($dir) {
      
        if (substr ($dir, -1) != '/')
          $dir .= '/';
          
        $path .= $dir;
      }
      
      if ($file)
        $path .= $file;
      
      if (!$path)
        $path = '/';

      elseif ($path[0] != '/')
        $path = "/$path";  
    }
    
    
    # build url from server and path
    
    if ($server) {
    
      if (substr ($server, -1) == '/') 
        $server = substr ($server, 0, -1);
    
      $url = "${server}${path}";
      break;
    }
    
    return  error ($cmd, "no 'url' option set, and cannot be constructed from a 'server' and an optional 'path' option");
    
  } while (false);
  

  # get protocol opt

  $protocol = get_opt ($prefix, $opts, 'protocol');
  
  if (!check_opt_if_set_type ($cmd, $protocol, 'protocol', 'enum:ftp|ftps|http|https'))
    return  false;
    
    
  # check the protocol is valid / accepted
    
  if (!$protocol) {
  
    $substr_6 = substr ($url, 0, 6);
    $substr_7 = substr ($url, 0, 7);
    $substr_8 = substr ($url, 0, 8);
  
    if      ($substr_6 == 'ftp://')     $protocol = 'ftp';
    elseif  ($substr_7 == 'ftps://')    $protocol = 'ftps';
    elseif  ($substr_7 == 'http://')    $protocol = 'http';
    elseif  ($substr_8 == 'https://')   $protocol = 'https';
    elseif  ($default_protocol)         $protocol = $default_protocol;
    else
      return  error ($cmd, "protocol '$protocol' is not supported");
      
    $opts['protocol'] = $protocol;
  }

  
  # check if the url has the protocol included
  
  if (substr ($url, 0, strlen ($protocol) + 3) != "${protocol}://")
    $url = "${protocol}://$url";
  
    
  # check if the path is a directory, and add a slash if so
  
  if (substr ($url, -1) != '/') {

    # get path is dir opt
    
    $path_is_dir = get_opt ($prefix, $opts, 'path_is_dir', false);
    
    if (!check_opt_set_type ($cmd, $path_is_dir, 'path_is_dir', 'boolean'))
      return  false;
      
      
    # add slash to end if necessary
    
    if ($path_is_dir)
      $url .= '/';
  }
  
  
  # set the url
  
  $curlopts['url'] = $url;
    
    
  # get port opt
  
  $port = get_opt ($prefix, $opts, 'port');
  
  if (!check_opt_if_set_type ($cmd, $port, 'port', 'integer'))
    return  false;
    
    
  # set the port curlopt
  
  if (!$port) {
  
    $port = $GLOBALS['curl_default_ports'][$protocol];
  }
  
  $curlopts['port'] = $port;
      
  
  # get user opt
  
  $tries = array ('user', 'usr');
  
  foreach ($tries as $try) {
  
    $user = get_opt ($prefix, $opts, $try);
  
    if (!check_opt_if_set_type ($cmd, $user, $try, 'string'))
      return  false;
    
    if ($user)
      break;
  }
    
  
  # get password opt
  
  $tries = array ('password', 'pwd');
  
  foreach ($tries as $try) {
  
    $password = get_opt ($prefix, $opts, $try);
  
    if (!check_opt_if_set_type ($cmd, $password, $try, 'string'))
      return  false;
    
    if ($password)
      break;
  }
  
  
  # set the authentication
  
  if ($user && $password) {
  
    $curlopts['userpwd'] = "$user:$password";
  }
  
  
    
  # get curlinfo opt

  $curlinfo = get_opt ($prefix, $opts, 'curlinfo');
  
  if (!check_opt_if_set_type ($cmd, $curlinfo, 'curlinfo', 'array_of_strings'))
    return  false;
    
    
  # get request retries opt
    
  $request_retries = get_opt_config_value ($prefix, $opts, 'request_retries', 3);
  
  if (!check_opt_set_type ($cmd, $request_retries, 'request_retries', 'integer'))   # change to having max 10
    return  false;
    
    
  ## messages ##
    
  # get exec msg opt
    
  $exec_msg = get_opt ($prefix, $opts, 'exec_msg', "executing curl request to url : $url");
  
  if (!check_opt_set_type ($cmd, $exec_msg, 'exec_msg', 'string,boolean'))
    return  false;
    
  
  # get exec retry msg opt
    
  $exec_retry_msg = get_opt ($prefix, $opts, 'exec_retry_msg', "previous request failed - trying again");
  
  if (!check_opt_set_type ($cmd, $exec_retry_msg, 'exec_retry_msg', 'string,boolean'))
    return  false;
    
    
  # get exec retry msg opt
    
  $success_msg = get_opt ($prefix, $opts, 'success_msg', "request successful");

  if (!check_opt_set_type ($cmd, $success_msg, 'success_msg', 'string,boolean'))
    return  false;
    
  
  # get exec retry msg opt
    
  $fail_msg = get_opt ($prefix, $opts, 'fail_msg', "request failed");
  
  if (!check_opt_set_type ($cmd, $fail_msg, 'fail_msg', 'string,boolean'))
    return  false;
    
    
  ## save to locations ##   
  
  # get save request head to file opt
  
  $request_head_file = get_opt ($prefix, $opts, 'save_request_head_to_file');
  
  if (!check_opt_if_set_type ($cmd, $request_head_file, 'save_request_head_to_file', 'string'))
    return  false;

    
  # get save request body to file opt
  
  $request_body_file = get_opt ($prefix, $opts, 'save_request_body_to_file');
  
  if (!check_opt_if_set_type ($cmd, $request_body_file, 'save_request_body_to_file', 'string'))
    return  false;
    
  
  # get save head to file opt

  $response_head_file = get_opt ($prefix, $opts, 'save_head_to_file');
  
  if (!check_opt_if_set_type ($cmd, $response_head_file, 'save_head_to_file', 'string'))
    return  false;
    
    
  # get save body to file opt
  
  $response_body_file = get_opt ($prefix, $opts, 'save_body_to_file');
    
  if (!check_opt_if_set_type ($cmd, $response_body_file, 'save_body_to_file', 'string'))
    return  false;
    
    
  # get max saved body size opt
  
  $max_saved_body_size = get_opt_config_value ($prefix, $opts, 'max_saved_body_size', 512*1024*1024);
  
  if (!check_opt_if_set_type ($cmd, $max_saved_body_size, 'max_saved_body_size', 'integer'))
    return  false;
    
    
  # get stderr opt
  
  $stderr = get_opt_config_value ($prefix, $opts, 'stderr');
  
  if (!check_opt_if_set_type ($cmd, $stderr, 'stderr', 'string'))
    return  false;
  
  
  # set stderr curlopt if necessary
  
  if ($stderr) {
  
    if (!make_dir_for_saving_file ($stderr, null, $cmd))
      return  false;
  
    $stderr_handle = fopen ($stderr, 'w');
    
    $curlopts['stderr'] = $stderr_handle;
  }
  
    
    
  # get the connection
  
  #$conn = curl_get_conn ($prefix, $opts, $cmd);
  
  #if (!$conn)
  #  return  false;
  
  #$opts['conn'] = $conn;
  #$c = $conn['handle'];
  
  /*
  $c = curl_init ();
  
  if (!$c)
    return  error ($cmd, "could not create curl resource");
  */
  
  # execute pre-exec functions
  
  if ($internal && $pre_exec_functions) {

    foreach ($pre_exec_functions as $function) {
    
      $r = call_user_func ($function, $prefix, $func_args, $cmd, $debug);
      
      if ($r === false)
        return  false;
    }
  }
  
  
  # set up the options
  
  if (!curl_set_curlopts ($prefix, $protocol, $curlopts, $cmd, $c, $internal, $debug))
    return  false;
    
  
  if ($save_to_file) {
  
  
    # backup old response file if set and exists
  
    if (is_file ($save_to_file) && $backup_old_saved_files && !backup_file ($save_to_file, null, $cmd))
      return  error ($cmd, "could not backup previously saved file : $save_to_file");
      

    # create parent directory of response file
  
    if (!make_dir (dirname ($save_to_file), $opts, $cmd))
      return  false;
  
  
    /*
    # create file handler for response file
  
    $save_to_file_handle = @fopen ($save_to_file, "w");
  
    if (!$save_to_file_handle)
      return  error ($cmd, "could not open response file : $save_to_file");
    */
    
    
    # add response file handler to request
      
    curl_setopt ($c, CURLOPT_FILE, $save_to_file_handle);
    
    
    # display message and add to $res
    
    debug_echo ($cmd, "saving response to file : $save_to_file");
    
    $res['response_file'] = $save_to_file;
    $res['file'] = $save_to_file;
  }
    
  
  # display exec message
  
  if ($exec_msg) {
    debug_echo ($cmd, "$exec_msg ...");
  }
  
  
  # execute request
  
  $success = false;
  
  for ($i=0; $i<$request_retries; $i++) {
  
    $r = curl_exec ($c);
    
    if ($r) {
      $success = true;
      break;
      
    } elseif ($exec_retry_msg) {
    
      debug_echo ($cmd, "$exec_retry_msg ...");
    }
  }

  
  # close file handles
  
  if (@$save_to_file_handle)
    fclose ($save_to_file_handle);
  
  if (@$stderr_handle)
    fclose ($stderr_handle);
  
  
  # display message about result (we add space to cover up any download monitor)
  
  if ($success) {
  
    if ($success_msg) {
      debug_echo ($cmd, "$success_msg                     ");
    }
    
  } elseif ($fail_msg) {
  
    $curl_error = strtolower (curl_error ($c));
  
    $fail_msg .= ", $curl_error";
    
    return  error ($cmd, "$fail_msg                        ");
  }
  
  
  # execute post-exec functions
  
  if ($internal && $post_exec_functions) {

    foreach ($post_exec_functions as $function) {

      $r = call_user_func ($function, $prefix, $func_args, $cmd, $debug);
      
      if ($r === false)
        return  false;
    }
  }
  
      
  # split the response into header and body
  
  $request_head = curl_getinfo ($c, CURLINFO_HEADER_OUT);
  $response_head_size = curl_getinfo ($c, CURLINFO_HEADER_SIZE);
  
  if ($save_to_file) {
  
  
    # get response body size
  
    $handle = fopen ($save_to_file, 'r');
    $response_head = ($response_head_size > 0 ? trim (fread ($handle, $response_head_size)) : '');    
    
    $stat = fstat ($handle);
    $response_body_size = $stat['size'] - $response_head_size;
    
    
    # check max size of response body file before putting in separate file
    
    if ($response_body_size > $max_saved_body_size) {
    
      warning ($cmd, "response body size is greater than max, so is not set : $response_body_size > $max_saved_body_size");
    
      $response_body = "[$save_to_file]";
      $no_reset_response_body_size = true;
      
    } else {
      
      $response_body =  ($response_body_size > 0 ? trim (fread ($handle, $response_body_size)) : '');
    }
    
    fclose ($handle);
  
  } else {
    
    $response_head = trim (substr ($r, 0, $response_head_size));
    $response_body = trim (substr ($r, $response_head_size));
  }
  
  $response_head_size = strlen ($response_head);
  
  if (!@$no_reset_response_body_size)
    $response_body_size = strlen ($response_body);
  
  
  # add main request and response variables to $res

  $res['request_url']         = $url;
  $res['request_head_size']   = strlen ($request_head);
  $res['response_head_size']  = $response_head_size;
  $res['response_body_size']  = $response_body_size;
  
  
  # save data to files if set and save in $res
  
  $save_to_files = array (
    'request_head'   => array ($request_head_file,   $request_head),
    #'request_body'   => array ($request_body_file,   $request_body),
    'response_head'  => array ($response_head_file,  $response_head),
    'response_body'  => array ($response_body_file,  $response_body),
  );
  

  # save data to files if set and save in $res
  
  foreach ($save_to_files as $name => $vars) {
  
    $file = $vars[0];
    $data = $vars[1];
    
    if ($file) {
  
      $report_name = str_replace ('_', ' ', $name);
  
      if (is_file ($file) && $backup_old_saved_files && !backup_file ($file, null, $cmd))
      return  error ($cmd, "could not backup previously saved $report_name file : $file");
    
      if (strlen ($data) == 0) {
    
        warning ($cmd, "$report_name is of zero size, so shall not be saved to file : $file");
      
      } else {
    
        if (!file_put_contents ($file, $data))
          return  error ($cmd, "could not save $report_name to file : $file");
      
        debug_echo ($cmd, "$report_name saved to file : $file");
      
        $res["${name}_file"] = $file;
      }
    }
    
    $res[$name] = $data;
  }

  
  # finalize curlopts
  
  if ($finalize_curlopts) {
  
    if (!curl_set_curlopts ($prefix, $protocol, $finalize_curlopts, $cmd, $c, $internal, $debug, true))
      return  false;
  }
  

  # execute finalize functions
  
  if ($internal && $finalize_functions) {

    foreach ($finalize_functions as $function) {
    
      $r = call_user_func ($function, $prefix, $func_args, $cmd, $debug);
      
      if ($r === false)
        return  false;
    }
  }
  
  
  # sort $res, display and return
  
  ksort ($res);
    
  curl_close ($c);
    
  return  $res;
}
  

function  curl_get_conn ($prefix, $opts, $cmd) {

  /*
  # merge opts
  
  $multi = get_opt ($prefix, $opts, 'multi', false);
  
  if (!check_opt_set_type ($cmd, $multi, 'boolean'))
    return  false;
  
  
  if ($multi)
    return  curl_init ();
  */ 


  # get and protocol
  
  $url = $opts['curlopts']['url'];
  $protocol = $opts['protocol'];
  $protocol_prefix_len = strlen ($protocol) + 3;
  
  if (substr ($url, 0, $protocol_prefix_len) == "${protocol}://")
    $url = substr ($url, $protocol_prefix_len);
  
  
  # get host
  
  $res = preg_match ('/^([^\/]*).*/', $url, $matches);
  
  if (!$res)
    return  error ($cmd, "host '$url' is not valid");
    
  $svr = $matches[1];
  
  
  # get conn name
  
  $conn_name = get_opt ($prefix, $opts, 'conn_name', 'default');
  
  if (!check_opt_set_type ($cmd, $conn_name, 'conn_name', 'string'))
    return  false;
  
    
  # check to see if the connection already exists
  
  $conns = 'curl_conns';
  
  $conn = @$GLOBALS[$conns][$protocol][$svr][$conn_name];
  
  if ($conn) {
    return  $conn;
  }
  
  # create a new connection
  
  $c = curl_init ();
  
  $conn = array (
    'handle'  => $c,
    'svr'     => $svr,
  );
  
  
  # create necessary array
  
  if (!@$GLOBALS[$conns])                     $GLOBALS[$conns] = array ();
  if (!@$GLOBALS[$conns][$protocol])          $GLOBALS[$conns][$protocol] = array ();
  if (!@$GLOBALS[$conns][$protocol][$svr])    $GLOBALS[$conns][$protocol][$svr] = array ();
    

  # save the conn and return
  
  $GLOBALS[$conns][$protocol][$svr][$conn_name] = $conn;
    
  return  $conn;
}


function  curl_set_curlopts_list ($list, &$c, $cmd, $debug) {


  # loop through all curlopts in list
  
  foreach ($list as $name => $value) {
  
  
    # display debug info
  
    if ($debug) {
    
      if (is_bool ($value)) {
      
        $value_str = $value ? 'true' : 'false';
        
      } else {
    
        $value_str = (string) $value;
      }
      
      debug_echo ($cmd, "setting curlopt : $name = $value_str");
    }
    
    
    # set the curlopt
    
    $res = curl_setopt ($c, constant ('CURLOPT_' . strtoupper ($name)), $value);
    
    if (!$res)
      return  error ($cmd, "could not set curl option '$name'");
  }
  
  return  true;
}


function  curl_set_curlopts ($prefix, $protocol, $curlopts, $cmd, &$c, $internal, $debug, $finalize = false) {


  if (!$finalize) {

    # set up the options and defaults

    $curl_options = $GLOBALS['curl_options'];

    $defaults = $curl_options['defaults'];
    $options  = $curl_options['options'];
    
    
    if (curl_set_curlopts_list ($defaults['all'], $c, $cmd, $debug) === false)
      return  false;
      
    if (curl_set_curlopts_list ($defaults[$protocol], $c, $cmd, $debug) === false)
      return  false;
  }
  
  
  # passed opts
  
  #if ($internal) {
  
    if (curl_set_curlopts_list ($curlopts, $c, $cmd, $debug) === false)
      return  false;
    
  #} else {
  
    #return  error ($cmd, "non-internal curl opts cannot be set yet");
  #}
  
  
  curl_setopt ($c, CURLINFO_HEADER_OUT, true);
  
  return  true;
}
  
  
function  curl_progress_function ($c, $download_size, $downloaded, $upload_size, $uploaded = null) {
 
 
  $downloaded_size_str = format_filesize ($downloaded);
 
  if ($download_size < 0) {
  
    $str = "$downloaded_size_str";
  
  } else {
 
    if ($downloaded == 0) {
    
      $pc = '0';
    
    } elseif ($download_size == 0) {
    
      $pc = '100';

    } else {
    
      $pc = ($downloaded / $download_size) * 100;
      $pc = sprintf ("%2.2f", $pc);
    }
    
    $str = "$pc% ($downloaded_size_str)";
  }
  
  # print download status (extra spaces added to clear previous entries that are pushed)
  
  echo "  Downloaded : $str           \r";
}


function  curl_finalize_set_split_key ($prefix, $func_args, $cmd, $debug, $key = 'files', $split = "\n") {


  # get opts and curlopts

  $res = &$func_args['res'];
  
  
  # get response
  
  $res[$key] = explode ($split, $res['response_body']);
  
  
  return  true;
}


function  curl_check_saved_file ($opts, $pipe, $cmd = __FUNCTION__, $prefix = 'curl') {


  # merge opts
  
  $opts = merge_opts ($opts, $pipe, 'file');

  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, 'use_saved_file', true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, 'use_saved_file', 'boolean'))
    return  false;
    
    
  # save the use download file opt and update
  
  $old_use_saved_file = get_config_value ("${prefix}_use_saved_file");
  
  set_config_value ("${prefix}_use_saved_file", $use_saved_file);
  set_config_value ("${prefix}_old_use_saved_file", $old_use_saved_file);
  
  
  # return if not using downloaded file
  
  if (!$use_saved_file) {
    return  array ("file" => null);
  }
  
  
  # get file opt
  
  $file = get_opt ($prefix, $opts, 'file');
  
  if (!check_opt_set_type ($cmd, $file, 'file', 'string'))
    return  false;
  
  
  # set the old execute
  
  $old_execute = get_config_value ("${prefix}_execute");

  set_config_value ("${prefix}_old_execute", $old_execute);
  
  
  # check to see if the file exists already
  
  if (!file_exists ($file))
    return  array ("file" => null);
    
    
  # cancel http request execution
  
  $prefix_upper = strtoupper ($prefix);
  
  debug_echo ($cmd, "using saved file '$file' instead of executing the $prefix_upper request");
  
  set_config_value ("${prefix}_execute", false);
  
  
  # store file path and return
  
  set_config_value ("${prefix}_checked_saved_file", $file);
  
  return  array (
    "file" => $file,
    "response_file" => $file
  );
}


function  curl_use_checked_saved_file ($opts, $pipe, $cmd = __FUNCTION__, $prefix = 'curl') {
  

  # merge opts

  $opts = merge_opts ($opts, $pipe, "use_saved_file");
  
  
  # get use downloaded file opt
  
  $use_saved_file = get_opt_config_value ($prefix, $opts, "use_saved_file", true);
  
  if (!check_opt_set_type ($cmd, $use_saved_file, "use_saved_file", "boolean"))
    return  false;
  
  
  # reset use download file opt
  
  $old_use_saved_file = get_config_value ("${prefix}_old_use_saved_file");
  
  set_config_value ("${prefix}_use_saved_file", $old_use_saved_file);
  
  
  # check to see if should use downloaded file or not
  
  if (!$use_saved_file)
    return  $pipe;
  

  # restore http request execution config
  
  $execute = get_config_value ("${prefix}_old_execute");
  
  set_config_value ("${prefix}_execute", $execute);
  
  
  # get checked downloaded file opt
  
  $file = get_config_value ("${prefix}_checked_saved_file");
    
  if (!check_opt_if_set_type ($cmd, $file, "${prefix}_checked_saved_file", "string"))
    return  false;
    
    
  # if file does not exist just pipe
    
  if (!$file)
    return  $pipe;
    
  
  # reset config and return
  
  set_config_value ("${prefix}_checked_saved_file", null);
  
  
  return  array (
    "file" => $file,
    "response_file" => $file,
  );
}

/*
  TODO: IN CURL
  
  # check to see if we should use a downloaded file
  
  if ($use_saved_file) {


    # check if response body file exists
  
    if (file_exists ($response_body_file)) {
    
      debug_echo ($cmd, "using saved response body file instead of executing the HTTP request : $response_body_file");

      $res['response_body_file']  = $response_body_file;
      $res['response_file']       = $response_body_file;
      $res['file']                = $response_body_file;
      
      return  $res;
    }
  
  
    # check if the save to file exists
  
    if (file_exists ($save_to_file)) {
    
      debug_echo ($cmd, "using saved file instead of executing the HTTP request : $save_to_file");

      $res['response_file'] = $save_to_file;
      $res['file']          = $save_to_file;
      
      return  $res;
    }
    

    # return immediately if there's a checked downloaded file

    if ($checked_saved_file) {
    
      $res['response_file'] = $checked_saved_file;
      $res['file']          = $checked_saved_file;
    
      return  $res;
    }
  }
*/

?>