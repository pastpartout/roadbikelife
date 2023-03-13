<?php
class JConfig {
	public $offline = false;
	public $offline_message = 'Bitte versuche es später noch mal. ';
	public $display_offline_message = 1;
	public $offline_image = '';
	public $sitename = 'roadbikelife';
	public $editor = 'tinymce';
	public $captcha = '0';
	public $list_limit = 20;
	public $access = 1;
	public $debug = true;
	public $debug_lang = false;
	public $debug_lang_const = true;
	public $dbtype = 'mysqli';
	public $host = 'db';
	public $user = 'root';
	public $password = 'root';
	public $db = 'roadbikelife_redesign_2023';
	public $dbprefix = 'gn5kx_';
	public $live_site = '';
	public $secret = 'icdZpHfn3TzNPVbF';
	public $gzip = false;
	public $error_reporting = 'none';
	public $helpurl = 'https://help.joomla.org/proxy?keyref=Help{major}{minor}:{keyref}&lang={langcode}';
	public $ftp_host = '';
	public $ftp_port = '';
	public $ftp_user = '';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '0';
	public $offset = 'Europe/Berlin';
	public $mailonline = true;
	public $mailer = 'smtp';
	public $mailfrom = 'info@roadbikelife.de';
	public $fromname = 'roadbikelife.de';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = false;
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'localhost';
	public $smtpsecure = 'none';
	public $smtpport = 25;
	public $caching = 0;
	public $cache_handler = 'file';
	public $cachetime = 9999;
	public $cache_platformprefix = true;
	public $MetaDesc = 'Auf diesem Rennrad-Blog teile ich meine Rennradtouren, zeige euch geeignete Strecken rund um Leipzig, gebe Reviews zu Rennrad-Events oder teste Produkte';
	public $MetaKeys = 'Rennrad, Blog, Leipzig,Rennrad Touren, Rennrad Strecken, Rennrad Ausfahrten, Rennradtreff, Leipzig ';
	public $MetaTitle = '1';
	public $MetaAuthor = true;
	public $MetaVersion = false;
	public $robots = '';
	public $sef = true;
	public $sef_rewrite = true;
	public $sef_suffix = false;
	public $unicodeslugs = true;
	public $feed_limit = 10;
	public $feed_email = 'none';
	public $log_path = 'var/www/html/logs';
	public $tmp_path = '/tmp';
	public $lifetime = 120;
	public $session_handler = 'database';
	public $shared_session = true;
	public $memcache_persist = '1';
	public $memcache_compress = '0';
	public $memcache_server_host = 'localhost';
	public $memcache_server_port = '11211';
	public $memcached_persist = true;
	public $memcached_compress = false;
	public $memcached_server_host = 'localhost';
	public $memcached_server_port = 11211;
	public $redis_persist = true;
	public $redis_server_host = 'localhost';
	public $redis_server_port = 6379;
	public $redis_server_auth = '';
	public $redis_server_db = 0;
	public $proxy_enable = false;
	public $proxy_host = '';
	public $proxy_port = '';
	public $proxy_user = '';
	public $proxy_pass = '';
	public $massmailoff = false;
	public $replyto = '';
	public $replytoname = '';
	public $MetaRights = '';
	public $sitename_pagetitles = 2;
	public $force_ssl = 0;
	public $session_memcache_server_host = 'localhost';
	public $session_memcache_server_port = '11211';
	public $session_memcached_server_host = 'localhost';
	public $session_memcached_server_port = 11211;
	public $session_redis_persist = 1;
	public $session_redis_server_host = 'localhost';
	public $session_redis_server_port = 6379;
	public $session_redis_server_auth = '';
	public $session_redis_server_db = 0;
	public $frontediting = 0;
	public $cookie_domain = '';
	public $cookie_path = '';
	public $asset_id = '1';
	public $session_name = '1TASQqIIuSFDOCDJ';
	public $dbencryption = 0;
	public $dbsslkey = '';
	public $dbsslcert = '';
	public $dbsslverifyservercert = false;
	public $dbsslca = '';
	public $dbsslcipher = '';
	public $cors = false;
	public $cors_allow_origin = '*';
	public $cors_allow_headers = 'Content-Type,X-Joomla-Token';
	public $cors_allow_methods = '';
	public $behind_loadbalancer = false;
	public $session_filesystem_path = '';
	public $session_metadata = true;
	public $block_floc = 1;
	public $log_everything = 0;
	public $log_deprecated = 0;
	public $log_priorities = array('0' => 'all');
	public $log_categories = '';
	public $log_category_mode = 0;
	public $cache_path = 'cache';
	public $session_metadata_for_guest = true;
}