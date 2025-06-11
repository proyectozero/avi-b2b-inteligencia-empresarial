<?php
/* General */
define('CFG_APP_URL', 'http://localhost/app.avib2b.com');
$cfg_chat_mode 	= 1;
$cfg_debug_mode = 1;

if ($cfg_debug_mode) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$cfg_sitetitle = "AVI B2B";
$cfg_sitedescription = "Copilo empresarial";

/* DB - AVIb2b */
$cfg_bdhost = 'localhost';
$cfg_bduser = 'avib2b_prod';
$cfg_bdpass = '';
$cfg_bdname = 'avib2b_app';

/* DB - Data werehouse */
$cfg_dw_dbhost = 'localhost';
$cfg_dw_dbuser = 'avib2b_prod';
$cfg_dw_dbpass = '';
$cfg_dw_dbname = 'avib2b_dw';

/* OpenAI */
define('CFG_OPENAI_KEY', 'sk-proj-0000-00000');

/* OpenAI for conversation */
define('CFG_OPENAI_MODEL_1', 'gpt-3.5-turbo');
define('CFG_OPENAI_TEMP_1', 0.7);
define('CFG_OPENAI_MAXTOKENS_1', 800);

/* OpenAI for Data Analyst */
define('CFG_OPENAI_MODEL_2', 'gpt-3.5-turbo');
define('CFG_OPENAI_TEMP_2', 0.3);
define('CFG_OPENAI_MAXTOKENS_2', 1000);

/* OpenAI for SQL Generator */
define('CFG_OPENAI_MODEL_3', 'gpt-3.5-turbo');
define('CFG_OPENAI_TEMP_3', 0.0);
define('CFG_OPENAI_MAXTOKENS_3', 300);

/* DialogFlow */
define('CFG_DIALOGFLOW_PROJECTID', 'avib2b-0000');

/* MyAVI */
define('CFG_MYAVIAPI_URL', '');
define('CFG_MYAVIAPI_TEMP', 0.6);
define('CFG_MYAVIAPI_MAXTOKENS', 300);

/* AVI Legal */
define('CFG_AVILEGALAPI_URL', '');

/* SSO - Google */
$cfg_sso_google_clientid 	 = '000000';
$cfg_sso_google_clientsecret = '000000';

//records
define('CFG_RECORDS_PARTSMS', 300000); // 60 seconds (60000) & 5 minutes (300000 ms)
define('CFG_RECORDS_PATH', 'uploads/records/');