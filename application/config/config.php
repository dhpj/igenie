<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['site_name'] = '지니'; //사이트명
$config['site_full_name'] = 'AI 스마트 매장관리시스템'; //사이트 풀네임
$config['no_img_path'] = '/dhn/images/leaflets/sample_img.jpg'; //NO IMAGE
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
$config['payment_bank_name'] = "기업은행"; //레드뱅킹 은행명
$config['payment_bank_number'] = "522-046936-04-037"; //레드뱅킹 계좌번호
$config['payment_bank_owner'] = "주식회사 대형네트웍스"; //레드뱅킹 예금주
$config['screen_tem_img'] = "/dhn/images/leaflets/tem/tem01.jpg"; //스마트전단 템플릿 기본 이미지
$config['screen_tem_bgcolor'] = "#47b5e5"; //스마트전단 템플릿 기본 배경색

// if($_SERVER['HTTP_HOST'] == "kakaobrandtalk.com"){ //개발서버의 경우
// 	//echo "개발<br>";
// 	$config['kakao_map_appkey'] = 'eeb5ad764dcbd695ccb00bf233c571d9'; //카카오 지도 API => 개발 : eeb5ad764dcbd695ccb00bf233c571d9, 운영 : 5eb6ce655a9dd1e9948326da08bfcbe2
// }else{
// 	//echo "운영<br>";
	$config['kakao_map_appkey'] = '5eb6ce655a9dd1e9948326da08bfcbe2'; //카카오 지도 API => 개발 : eeb5ad764dcbd695ccb00bf233c571d9, 운영 : 5eb6ce655a9dd1e9948326da08bfcbe2
// }

// $config['kakao_map_appkey'] = 'eeb5ad764dcbd695ccb00bf233c571d9';
// $config['kakao_map_appkey'] = '8f19970c0d5f741c07e31dba20d6ae2c';
//이미지 경로
// $config['igenie_path'] = "http://igenie.co.kr";
// $config['igenie_path'] = "http://175.199.47.43/";
$config['igenie_path'] = "http://14.43.241.107/";

//인포뱅크 flag 설정
// $config['ib_flag'] = "Y";
//dhn flag 설정
$config['ib_flag'] = "N";

//인포뱅크 테이블
// $config['ib_profile'] = "cb_wt_send_profile_ib";
// $config['ib_profile_group'] = "cb_wt_send_profile_group_ib";
// $config['ib_template'] = "cb_wt_template_ib";
// $config['ib_category'] = "cb_wt_template_category_ib";
// $config['ib_categoryid'] = "cb_wt_template_categoryid_ib";

//dhn 테이블
$config['ib_profile'] = "cb_wt_send_profile_dhn";
$config['ib_profile_group'] = "cb_wt_send_profile_group_dhn";
$config['ib_template'] = "cb_wt_template_dhn";
$config['ib_category'] = "cb_wt_template_category";
$config['ib_categoryid'] = "cb_wt_template_categoryid";

//dhn+대전연합
$config['eve_startTime1'] = "2023-12-01 00:00:00";
$config['eve_expTime1'] = "2024-01-01 00:00:00";

$config['eve1_member'] = array("3","1260","962","1294","911","883","859","768","830","2574","2542","2409","2312","895","769");

//티지
$config['eve_startTime2'] = "2022-08-08 00:00:00";
$config['eve_expTime2'] = "2022-09-01 00:00:00";

$config['eve2_member'] = array("962");


//채널 친구추가 쿠폰 이벤
$config['eve0_member'] = array("3","1260","1294","911");

$config['eve_startTime0'] = "2022-12-05 00:00:00";
$config['eve_expTime0'] = "2023-01-01 00:00:00";
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
//$config['base_url'] = 'http://igenie.co.kr/';
$config['base_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
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
//$config['cookie_path']  = '/; SameSite=None';
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
    array('controller' => 'bizmsgapi', 'method' => 'alimtalk_v'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk2nd'),
	array('controller' => 'bizmsgapi', 'method' => 'alimtalk2nds'),
    array('controller' => 'bizmsgapi', 'method' => 'testalimtk'), //2020-09-15 샘플발송신청 추가
    array('controller' => 'bizmsgapi', 'method' => 'sample_img_alimtk'), //2020-09-15 샘플발송신청 추가
    array('controller' => 'bizmsgapi', 'method' => 'msg'),
    array('controller' => 'crontab', 'method' => 'schedule'), //2020-10-05 스케쥴실행 추가
    array('controller' => 'crontab', 'method' => 'check_dormancy'), //2021-12-30 스케쥴실행 추가
	array('controller' => 'ad', 'method' => 'v'),
	array('controller' => 'ad', 'method' => 'index'),
    array('controller' => 'login', 'method' => 'loginapi'),
    array('controller' => 'login', 'method' => 'movetoapi'),
    //array('controller' => 'block_list', 'method' => 'add_block'),
	//array('controller' => 'block_list', 'method' => 'search_block'),
	array('controller' => 'survey', 'method' => 'index'),
	array('controller' => 'survey', 'method' => 'report'),
	array('controller' => 'swchk', 'method' => 'index'),
	array('controller' => 'block_list', 'method' => 'get_block_list'),
	array('controller' => 'autologin', 'method' => 'login_profile_key'), //타사이트에서 프로필키로 자동 로그인 요청
	array('controller' => 'login', 'method' => 'igenie_loginapi'), //타사이트에서 프로필키로 자동 로그인 요청
	array('controller' => 'untact', 'method' => 'voucher'), //바우처 신청 Form
	array('controller' => 'untact', 'method' => 'save'), //바우처 신청 저장 처리
    array('controller' => 'untact', 'method' => 'price'), //바우처 요금 안내 페이지
    array('controller' => 'nicepayapi', 'method' => 'get_nicepay'), //나이프세이 정산
    array('controller' => 'nicepayapi', 'method' => 'get_cardconfirm'), //나이프세이 정산
    array('controller' => 'posapi', 'method' => 'import_tel'),      // 포스기 고객전화번호 전송 API
    array('controller' => 'posapi', 'method' => 'import_goods'),    // 포스기 전단지 제품 전송 API
    array('controller' => 'posapi', 'method' => 'login'),           // 포스기에서 login API
    array('controller' => 'posapi', 'method' => 'import_goods_plus'),    // 포스기 전단지및 플친몰 제품 전송 API
    array('controller' => 'posapi', 'method' => 'export_order'),    // 플친몰 주문내역 Export API
    array('controller' => 'posapi', 'method' => 'export_genie_order'),    // 지니 주문내역 Export API
    array('controller' => 'posapi', 'method' => 'export_order_success'),    // 플친몰 주문내역 Export 성공 설정 API
    array('controller' => 'posapi', 'method' => 'export_order_successes'),    // 플친몰 주문내역 Export 성공 설정 API(다중)
    array('controller' => 'posapi', 'method' => 'export_genie_order_success'),    // 지니 주문내역 Export 성공 설정 API(다중)
    array('controller' => 'posapi', 'method' => 'set_order_status'),    // 플친몰 주문내역 상태 설정 API
    array('controller' => 'posapi', 'method' => 'set_order_statuses'),    // 플친몰 주문내역 상태 설정 API(다중)
    array('controller' => 'posapi', 'method' => 'set_genie_order_status'),    // 지니 주문내역 상태 설정 API(다중)
    array('controller' => 'posapi', 'method' => 'get_order_cancel'),    // 플친몰 주문취소 상태확인 API
    array('controller' => 'posapi', 'method' => 'get_order_cancels'),    // 플친몰 주문취소 상태확인 API(다중)
    array('controller' => 'posapi', 'method' => 'get_genie_order_cancel'),    // 지니 주문취소 상태확인 API(다중)
    array('controller' => 'ibresult', 'method' => 'proc'),
    array('controller' => 'login', 'method' => 'nicedocu'),
    array('controller' => 'api', 'method' => 'get_mem_coin'),
    array('controller' => 'untact', 'method' => 'ss_save'), //바우처 신청 저장 처리
    array('controller' => 'get_short', 'method' => 'index'), //바우처 신청 저장 처리
		array('controller' => 'qrcom', 'method' => 'api_write_form'),
		array('controller' => 'qrcom', 'method' => 'qr'),
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

//나이스페이
$config['USRID'] = 'dhn202301';
$config['MID'] = "dhn202301m"; //상점아이디
$config['merchantKey'] = "A3A+X62sJCaEnrCkqnh1LPp5iTrN1KJAonDuQ7ea92nM+xeleo0tBIXHnEwclNvFW+lSev3F6iKGMypuEvPvuw=="; //상점키
$config['FEE'] = "1";

$config["refund_code"] = array(
    "2001"
  , "2211"
);

$config["success_code"] = array(
    "3001"
  , "4000"
  , "4100"
  , "A000"
  , "7001"
);

$config['bank'] = array(
    '01' => '비씨'
  , '02' => 'KB국민'
  , '03' => '하나(외환)'
  , '04' => '삼성'
  , '06' => '신한'
  , '07' => '현대'
  , '08' => '롯데'
  , '09' => '한미'
  , '10' => '신세계한미'
  , '11' => '씨티'
  , '12' => 'NH채움'
  , '13' => '수협'
  , '14' => '신협'
  , '15' => '우리'
  , '16' => '하나'
  , '21' => '광주'
  , '22' => '전북'
  , '23' => '제주'
  , '24' => '산은캐피탈'
  , '25' => '해외비자'
  , '26' => '해외마스터'
  , '27' => '해외다이너스'
  , '28' => '해외AMX'
  , '29' => '해외JCB'
  , '31' => 'SK-OK CASH-BAG'
  , '32' => '우체국'
  , '33' => '저축은행'
  , '34' => '은련'
  , '35' => '새마을금고'
  , '36' => 'KDB산업'
  , '37' => '카카오뱅크'
  , '38' => '케이뱅크'
  , '39' => 'PAYCO 포인트'
  , '40' => '카카오머니'
  , '41' => 'SSG머니'
  , '42' => '네이버포인트'
);

$config['account_bank'] = array(
    '01' => 'KDB산업은행'
  , '02' => 'IBK기업은행'
  , '03' => '한국수출입은행'
  , '04' => 'NH농협은행'
  , '05' => 'SH수협은행'
  , '06' => 'KB국민은행'
  , '07' => '신한은행'
  , '08' => '우리은행'
  , '09' => 'KEB하나은행'
  , '10' => 'SC제일은행'
  , '11' => '한국씨티은행'
  , '12' => '대구은행'
  , '13' => '부산은행'
  , '14' => '경남은행'
  , '15' => '광주은행'
  , '16' => '전북은행'
  , '17' => '제주은행'
  , '18' => '케이뱅크'
  , '19' => '카카오뱅크'
  , '20' => '토스뱅크'
  , '21' => '새마을금고'
  , '22' => '신협'
);


//DB 열고 닫기 패스워드
$config['db_pwd'] = 'dhn7985!';

$config['salesman'] = array(
    5
  , 15
  , 7
  , 44
  , 45
  , 11
  , 28
);

$config['send_amount'] = array(
    5.28 //알림톡
);

$config['kakao_keys'] = array(
    '7f30b2daa9694d84730290bd2c5e872a'
  , 'c30ba6fb82a8137a2387ddcce6a6f4f2'
  , 'b9e9320da5a42978e0676b7d96ddc90a'
  , 'e11ca00d0af6cda96cd63bfa4837d6e7'
  , '6ced862eb1e344e7be412b261f2429d6'
  , '7e1f63bd9163e9bb2678ca74bdc61312'
  , 'c1a43d6321d9cd84aa3a5ab3393db526'
  , '3562ad3062f752d5800dddc86b171bfb'
  , 'f8ab59e65195948d30b89b68f9853cd6'
  , 'f6365aa40bc6866acdce09a02d7858d2'
  , '02849925842cb5d6f7ce0dfa475a90fe'
  , 'c79a68732ae185b9c6eea86b49041afb'
  , '7e44850c58428a761966a8a338dc5456'
  , '51853183f6813ea2c0e331ba13d2b3ed'
  , '7bd103af76dd66cb88f0ba5719764f17'
  , '2e47f01331e923cee706ff39a0cd91c9'
  , '4b440fb30e588c7019448efd8dc7b6cf'
  , 'b5e1f83a6bff551387a5decb65b3aeac'
  , 'c5ae2fa1632c893f93e593537aba28ec'
  , '7817e8eb48fa006efc5b69fb2096be6e'
  , 'fca56167837479a4cf79b5e5f2fa7360'
  , '1ffbcbcd8c81dc78b6ba317af9912d97'
  , 'f7394d4297a309b9b6209e2390de6af3'
  , '0d06cb74fbfb258a70ff207e02cf6060'
  , '0b583e85fe75cf5f76474443ea5f0d04'
  , '165d99cae8e5eebcd308a3827969606d'
  , '6a6c46c6b206a3d4750df62d3d3051c6'
  , '7ad277ea53424feda7413a137e853c87'
  , 'a393d26967060600422e47d6c0bf73c9'
  , '95ed3263ca83973b0a7de77e3e6ad077'
  , '9f4ecbf955e74a9c8b706631b0fcf0ae'
);

$config['sectors'] = array(
    '1' => '정육점'
  , '2' => '마트'
  , '3' => '카페'
  , '4' => '뷰티'
  , '5' => '안경'
  , '99' => '그 외 업종'
);

// 해피콜
$config['hc_category'] = array(
    1 => '해피콜'
  , 2 => '문의/불만사항'
  , 9 => '기타'
);

//고객의 소리
// 해피콜
$config['voc_cateogry'] = array(
    1 => '건의사항'
  , 2 => '불편사항'
);

$config['kakao_conf'] = array(
    'dhn_rest_key' => 'a108262ceef995a397922c92dd64a42f'
  , 'dhn_js_key' => 'd550e296282facc6b50cedaba778a9ac'
  , 'dhn_login_redirect_uri' => 'http://igenie.co.kr/home'
  , 'dhn_logout_redirect_uri' => 'http://igenie.co.kr/home'
);

$config['rep_tem'] = '500';

$config['price_display'] = array(
    2406, 2410
);

$config['adid'] = '442247';

$config['tmp_result_code'] = array(
    '200' => '요청성공'
  , '400' => '해당업체가 없거나, 요청값이 적절하지 않음'
  , '403' => '권한 없음'
  , '405' => '파라미터 오류'
  , '504' => '템플릿 코드 중복'
  , '505' => '템플릿 이름 중복'
  , '506' => '템플릿 내용이 1000자 초과'
  , '507' => '유효하지 않은 발신 프로필'
  , '508' => '요청한 데이터가 없음, 삭제 상태의 데이터 요청 시 응답'
  , '509' => '요청을 처리할수 있는 상태가 아님 (ex: 템플릿 검수 요청이 가능한 상태가 아닙니다.)'
  , '510' => '템플릿의 버튼/바로연결 형식이 유효하지 않음'
  , '511' => '대표링크/버튼/바로연결의 링크가 유효하지 않음'
  , '512' => '허브파트너는 발신프로필 추가 및 그룹내 발신프로필 추가가 제한된 상태'
  , '513' => '메시지 결과 수신 채널이 올바르지 않음'
  , '514' => '비즈니스 인증이 필요한 카카오톡 채널'
  , '519' => '등록하려는 발신프로필 채널의 고객센터 정보 입력 필요'
  , '525' => '템플릿의 카테고리가 유효하지 않음'
  , '530' => '유효하지 않은 플러그인'
  , '600' => '이미지 업로드 실패'
  , '610' => '파일 업로드 실패'
  , '611' => '첨부파일의 크기가 50MB를 초과'
  , '612' => '첨부파일 형식이 유효하지 않음'
  , '613' => '첨부파일의 개수가 10개를 초과'
  , '614' => '첨부파일이 존재 하지 않음'
  , '801' => '발신프로필 등록이 차단된 상태'
  , '802' => '발신프로필 등록이 차단된 상태'
  , '803' => '발신프로필 등록이 차단된 상태'
  , '804' => '발신프로필 등록이 차단된 상태'
  , '805' => '발신프로필 등록이 차단된 상태'
  , '811' => '발신프로필 등록이 차단된 허브파트너'
  , '3018' => '메시지를 전송할 수 없음'
);

$config['at_pword'] = '[
    "행사","특가","상품","비트코인","꽁머니","불법사기","폰테크","INVEST","invest","인베스트주식투자","중금리","주식초보","알트코인","이더리움","세력주","클릭","관망",
    "bitcoin","Bitcoin","물린종목","존버","로또9단","불장","꽃계열850","한승보","채무통합상품","배팅","체험리딩","개인종목","폰토스","로또구단","유료업체","불법업체","Catfish01",
    "seola7757","빚더미","부채통합","손실복구","초보주식","무료정보","한결인베스트","현금사은품","당일현금","로x또리치","채무통합","채무컨설팅","정보공유방","급등주","소액바이너리옵션",
    "무료정보방","매매기법","로얄x또","단타","주린이","바로대출","대출지원","대출이자지원","금리지원","이자지원","한도비교","무직자대출","금리정보","바로대출","서민지원","서민통합지원센터",
    "서민대출","금리컨설팅","연무산업","대부","대부업","채무통합","채무컨설팅","대출컨설팅","대출통합","국민지원","국민지원패스","급등주","추천주","주식리딩","무료리딩","유료리딩","VIP컨설팅",
    "VIP스탁","입장코드","무료체험방","VIP투자","VIP입장코드","상담비밀번호","도박","카지노","토토","배팅","무료충천","무료현금","무료포인트","실시간환전","체험머니","포커","바둑이","바다이야기",
    "경마","맞고","고스톱","사설놀이터","꽃계열850","경주","경륜","GAMBLE","꽁머니","파워볼","바카라","라이브스코어","블랙잭","코인","암호화폐","가상화폐","coinacone","전자담배","담배","소주",
    "맥주","와인","주류","막걸리","전통주","성인","애인대행","만남주선","야설","단체미팅","야한","섹시속옷","정보방","놀이터","한결인베스트","투자그룹","부채통합","무료정보방","매매기법","주린이",
    "소액","바이너리옵션","단타","매매기법","DGBbank","DGB상품"
]';

$config['ft_pword'] = '[
    "비트코인","꽁머니","불법사기","폰테크","INVEST","invest","인베스트주식투자","중금리","주식초보","알트코인","이더리움","세력주","클릭","관망","bitcoin","Bitcoin","물린종목","존버","로또9단",
    "불장","꽃계열850","한승보","채무통합상품","배팅","체험리딩","개인종목","폰토스","로또구단","유료업체","불법업체","Catfish01","seola7757","빚더미","부채통합","손실복구","초보주식","무료정보",
    "한결인베스트","현금사은품","당일현금","로x또리치","채무통합","채무컨설팅","정보공유방","급등주","소액바이너리옵션","무료정보방","매매기법","로얄x또","단타","주린이","바로대출","대출지원",
    "대출이자지원","금리지원","이자지원","한도비교","무직자대출","금리정보","바로대출","서민지원","서민통합지원센터","서민대출","금리컨설팅","연무산업","대부","대부업","채무통합","채무컨설팅",
    "대출컨설팅","대출통합","국민지원","국민지원패스","급등주","추천주","주식리딩","무료리딩","유료리딩","VIP컨설팅","VIP스탁","입장코드","무료체험방","VIP투자","VIP입장코드","상담비밀번호","도박",
    "카지노","토토","배팅","무료충천","무료현금","무료포인트","실시간환전","체험머니","포커","바둑이","바다이야기","경마","맞고","고스톱","사설놀이터","꽃계열850","경주","경륜","GAMBLE","꽁머니",
    "파워볼","바카라","라이브스코어","블랙잭","코인","암호화폐","가상화폐","coinacone","전자담배","담배","소주","맥주","와인","주류","막걸리","전통주","성인","애인대행","만남주선","야설",
    "단체미팅","야한","섹시속옷","정보방","놀이터","한결인베스트","투자그룹","부채통합","무료정보방","매매기법","주린이","소액","바이너리옵션","단타","매매기법","DGBbank","DGB상품"
]';

$config['msg_pword'] = '[
    "성인","대출","도박","의약품","비트코인","꽁머니","불법사기","폰테크","INVEST","invest","인베스트주식투자","중금리","주식초보","알트코인","이더리움","세력주","클릭","관망","bitcoin",
    "Bitcoin","물린종목","존버","로또9단","불장","꽃계열850","한승보","채무통합상품","배팅","체험리딩","개인종목","폰토스","로또구단","유료업체","불법업체","Catfish01","seola7757","빚더미",
    "부채통합","손실복구","초보주식","무료정보","한결인베스트","현금사은품","당일현금","로x또리치","채무통합","채무컨설팅","정보공유방","급등주","소액바이너리옵션","무료정보방","매매기법","로얄x또",
    "단타","주린이","바로대출","대출지원","대출이자지원","금리지원","이자지원","한도비교","무직자대출","금리정보","바로대출","서민지원","서민통합지원센터","서민대출","금리컨설팅","연무산업","대부",
    "대부업","채무통합","채무컨설팅","대출컨설팅","대출통합","국민지원","국민지원패스","급등주","추천주","주식리딩","무료리딩","유료리딩","VIP컨설팅","VIP스탁","입장코드","무료체험방","VIP투자",
    "VIP입장코드","상담비밀번호","카지노","토토","배팅","무료충천","무료현금","무료포인트","실시간환전","체험머니","포커","바둑이","바다이야기","경마","맞고","고스톱","사설놀이터",
    "꽃계열850","경주","경륜","GAMBLE","꽁머니","파워볼","바카라","라이브스코어","블랙잭","코인","암호화폐","가상화폐","coinacone","전자담배","담배","소주","맥주","와인","주류","막걸리",
    "전통주","성인","애인대행","만남주선","야설","단체미팅","야한","섹시속옷","정보방","놀이터","한결인베스트","투자그룹","부채통합","무료정보방","매매기법","주린이","소액","바이너리옵션",
    "단타","매매기법","DGBbank","DGB상품"
]';
?>
