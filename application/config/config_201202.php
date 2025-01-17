<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['developer_ip'] = '182.208.91.234';
$config['use_multi_agent'] = '0';
$config['phn_agent_name'] = array("prcom"=>"PR컴퍼니");
$config['sms_agent_name'] = array("naself"=>"나셀프");
$config['send_agent_name'] = array("prcom"=>"PR컴퍼니", "naself"=>"IT희망나눔");
$config['send_agent'] = array("prcom"=>"1", "naself"=>"2");
$config['send_agent_table'] = array("prcom"=>"dhn.cb_pr_tran", "naself"=>"qtsms.SMSQ_SEND");
$config['send_agent_regdate'] = array("prcom"=>"PR_SENDDATE", "naself"=>"sendreq_time");
$config['send_agent_fail'] = array("prcom"=>"update dhn.cb_pr_tran set PR_STATUS='2', PR_RSLT='55'", "naself"=>"");
$config['refund_agent'] = array("prcom"=>"3", "naself"=>"4");
$config['phn_agent'] = array("prcom"=>"P", "naself"=>"");
$config['sms_agent'] = array("prcom"=>"", "naself"=>"S");
$config['lms_agent'] = array("prcom"=>"", "naself"=>"L");
$config['mms_agent'] = array("prcom"=>"", "naself"=>"M");

/**
 * CiBoard 주 : URL 주소에 쓰이는 URI 부분의 첫번째 요소를 정의합니다.
 * 예를 들어 갤러리 게시판을 세팅할 경우
 * http://www.도메인.com/board/gallery 와 같이 될 때에 board 라는 부분을 다른 이름으로 변경할 수 있습니다.
 */
$config['uri_segment_admin'] ='admin';  //관리자 페이지 주소
$config['uri_segment_board'] ='board';  //게시판 목록 부분 주소
$config['uri_segment_write'] ='write';  //게시글 쓰기 주소
$config['uri_segment_reply'] ='reply';  //게시물 답변하기 주소
$config['uri_segment_modify'] ='modify';  //게시글 수정하기 주소
$config['uri_segment_rss'] ='rss';  // RSS 주소
$config['uri_segment_group'] ='group';  //게시판 그룹 메인 주소
$config['uri_segment_document'] ='document';  //일반 페이지 주소
$config['uri_segment_faq'] ='faq';  //FAQ 페이지 주소
$config['uri_segment_cmall_item'] ='item';  //Cmall item 페이지 주소

//게시글 주소 ( http://www.ciboard.co.kr/post/123  과 같은 형식의 post 부분)
$config['uri_segment_post'] ='post';

//게시글 주소형식
// A :  http://www.ciboard.co.kr/post/123 과 같이 uri_segment_post  와 post_id 가 순서대로 주소에 붙는 형식
// B :  http://www.ciboard.co.kr/boardkey/post/123 과 같이 boardkey, uri_segment_post , post_id 가 순서대로 주소에 붙는 형식
// C :  http://www.ciboard.co.kr/post/boardkey/123 과 같이 uri_segment_post , boardkey, post_id 가 순서대로 주소에 붙는 형식
$config['uri_segment_post_type'] ='A';


/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/

/**
 * CiBoard 주 : 아래의 값을 꼭 입력하여주세요.
 * 루트에 설치하는 경우 올바른 예 ) http://www.test.com/
 * 루트에 설치하는 경우 잘못된 예 ) http://www.test.com/index.php
 * 서브에 설치하는 경우 올바른 예 ) http://www.test.com/subdir/
 * 서브에 설치하는 경우 잘못된 예 ) http://www.test.com/subdir/index.php
 */
//$config['base_url'] = 'http://210.114.225.54';
//$config['base_url'] = 'http://o2omsg.com/';
$config['base_url'] = 'http://igenie.co.kr/';
if (empty($config['base_url'])) exit("&dollar;config&lsqb;&apos;base_url&apos;&rsqb;  need to be set up in application/config/config.php");  // base_url 의 값을 입력하신 후에는 여기 if 문 자체를 주석처리해도 좋습니다

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/

/**
 * CiBoard 주 : 아래의 값은 입력하실 필요가 없습니다.
 * index.php 라고 입력하지 말아주세요. 그냥 입력하지 않으시면 됩니다
 */
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/

/**
 * CiBoard 주 : 아래의 값은 변경하실 필요가 없습니다.
 */
$config['uri_protocol'] = 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/

/**
 * CiBoard 주 : 아래의 값은 입력하실 필요가 없습니다.
 */
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/

/**
 * CiBoard 주 : 아래의 값은 변경하실 필요가 없습니다.
 */
$config['language'] = 'korean';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/

/**
 * CiBoard 주 : 아래의 값은 변경하실 필요가 없습니다.
 */
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/

/**
 * CiBoard 주 : 씨아이보드는 기본적으로 후크 기능을 사용하므로, 아래의 값을 변경하시면 안됩니다.
 */
$config['enable_hooks'] = TRUE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/

/**
 * CiBoard 주 : 씨아이보드는 기본적으로 CB_ prefix 로 클래스를 생성하여 배포합니다..
 * 아래의 값을 변경하기 원하시는 경우, 씨아이보드에서 배포되는 클래스명의 prefix 를 모두 알맞게 변경해주세요
 */
$config['subclass_prefix'] = 'CB_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/

/**
 * CiBoard 주 : 씨아이보드는 composer 기능을 현재는 사용하고 있지 않습니다.
 * 즉 아래의 값을 변경하실 필요가 없습니다
 */
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/

/**
 * CiBoard 주 : 로그를 어느 정도 수준에서 남길지 결정합니다..
 * 0 부터 4 사이에 원하시는 값으로 변경하시면 됩니다
 */
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/

/**
 * CiBoard 주 : 로그 경로는 기본적으로 application/logs 로 설정되어있습니다.
 * log 가 쌓이는 디렉토리 경로를 변경하기 원하시는 경우에만 입력해주세요
 * 입력하지 않으시면 application/logs 에 쌓이는 것을 확인하실 수 있습니다
 */
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/

/**
 * CiBoard 주 : 로그 파일 확장자는 기본적으로 php 입니다.
 * 다른 확장자로 변경하기 원하시는 경우에만 입력해주세요
 */
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/

/**
 * CiBoard 주 : 아래의 값을 특별히 변경하실 필요가 없습니다
 */
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/

/**
 * CiBoard 주 : 로그파일명 포맷입니다. 원하시는 경우 필요한 포맷으로 변경하셔도 좋습니다
 */
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/

/**
 * CiBoard 주 : 캐시 경로는 기본적으로 application/cache 로 설정되어있습니다.
 * 캐시가 쌓이는 디렉토리 경로를 변경하기 원하시는 경우에만 입력해주세요
 * 입력하지 않으시면 application/cache 에 쌓이는 것을 확인하실 수 있습니다
 */
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/

/**
 * CiBoard 주 : 아래의 값을 변경하실 필요가 없습니다
 */
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/

/**
 * CiBoard 주 : 아래의 값을 입력해주세요
 * 입력하신 값은 외부에 공개되지 않도록 주의해주세요
 */
$config['encryption_key'] = 'dbwlrudtjd';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/

/**
 * CiBoard 주 : 원하시는 경우 세션 환경값을 변경하여 주세요
 */
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
//$config['sess_save_path'] =  APPPATH.'cache/session/';
$config['sess_save_path'] =  NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/

/**
 * CiBoard 주 : 쿠키 정보를 알맞게 입력해주세요
 * 특별한 경우가 아니면, 기본적으로 cookie_domain 값만 변경하시면 됩니다.
 */
$config['cookie_prefix'] = '';
$config['cookie_domain'] = ''; // .ciboard.co.kr 와 같이 맨 앞에 . 을 찍고 도메인 명을 적습니다
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/

/**
 * CiBoard 주 : 아래의 값을 변경하지 말아주세요.
 */
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/

/**
 * CiBoard 주 : 아래의 값을 변경하지 말아주세요.
 */
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/

/**
 * CiBoard 주 : csrf protection 을 사용하지 않고 싶은 경우 FALSE 로 변경하시면 됩니다.
 * 그러나 보안상의 위험이 따르므로 TRUE 로 설정해 놓으시길 강력히!! 추천합니다
 */
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_test_name'; // 값을 변경하지 말아주세요
$config['csrf_cookie_name'] = 'csrf_cookie_name'; // 값을 변경하지 말아주세요
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array(
	array('controller' => 'payment', 'method' => 'kcp_return_result'),
	array('controller' => 'payment', 'method' => 'kcp_order_approval'),      //kcp 모바일 soap
	array('controller' => 'payment', 'method' => 'kcp_order_approval_form'),      //kcp 모바일결제
	array('controller' => 'payment', 'method' => 'lg_return_result'),
	array('controller' => 'payment', 'method' => 'lg_markethashdata'),
	array('controller' => 'payment', 'method' => 'lg_returnurl'),
	array('controller' => 'payment', 'method' => 'lg_noteurl'),
	array('controller' => 'payment', 'method' => 'lg_mispwap'),
	array('controller' => 'payment', 'method' => 'lg_cancelurl'),
	array('controller' => 'deposit', 'method' => 'inicisweb'),            //이니시스 웹 표준 결제 ( 예치금 )
	array('controller' => 'cmall', 'method' => 'inicisweb'),            //이니시스 웹 표준 결제 ( 컨텐츠몰 )
	array('controller' => 'payment', 'method' => 'inicis_approval'),            //이니시스 모바일 결제 NEXT
	array('controller' => 'payment', 'method' => 'inicis_noti'),                //이니시스 모바일 결제 NOTI
	array('controller' => 'payment', 'method' => 'inicis_return_result'),                //이니시스 pc 가상계좌 url
	array('controller' => 'editorfileupload', 'method' => 'smarteditor'),
	array('controller' => 'editorfileupload', 'method' => 'ckeditor'),
	array('controller' => 'selfcert', 'method' => 'kcb_ipin_return'),
	array('controller' => 'selfcert', 'method' => 'kcb_phone_return'),
	array('controller' => 'rbank_proc', 'method' => 'process'),
	array('controller' => 'CouponView', 'method' => 'index'),
	array('controller' => 'CouponView', 'method' => 'approval'),
	array('controller' => 'Short_url', 'method' => 'coupon'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk_test'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk2nd'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk2nds'),
	array('controller' => 'bizmsgapi', 'method' => 'testalimtk'), //2020-09-15 샘플발송신청 추가
	array('controller' => 'crontab', 'method' => 'schedule'), //2020-10-05 스케쥴실행 추가
	array('controller' => 'ad', 'method' => 'v'),
	array('controller' => 'ad', 'method' => 'index'),
    array('controller' => 'login', 'method' => 'loginapi'),
    //array('controller' => 'block_list', 'method' => 'add_block'),
	//array('controller' => 'block_list', 'method' => 'search_block'),
	array('controller' => 'survey', 'method' => 'index'),
	array('controller' => 'survey', 'method' => 'report'),
	array('controller' => 'swchk', 'method' => 'index'),
	array('controller' => 'block_list', 'method' => 'get_block_list'),
	array('controller' => 'autologin', 'method' => 'login_profile_key'), //타사이트에서 프로필키로 자동 로그인 요청
);

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';



/**
 * CiBoard 주 : 아래의 값을 변경하지 말아주세요.
 * 버전 체크용으로 사용됩니다
 */
$config['ciboard_website'] = '';
?>
