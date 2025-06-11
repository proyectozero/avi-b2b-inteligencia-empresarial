<?php
/* General */
define('CFG_APP_URL', 'http://localhost:8000');
$cfg_chat_mode = 1;
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
$cfg_bduser = 'root';
$cfg_bdpass = '';
$cfg_bdname = 'avib2b_app';

/* DB - Data werehouse */
$cfg_dw_dbhost = 'localhost';
$cfg_dw_dbuser = 'root';
$cfg_dw_dbpass = '';
$cfg_dw_dbname = 'avib2b_dw';

/* OpenAI */
define('CFG_OPENAI_KEY', 'dummy-key');

/* OpenAI for conversation */
define('CFG_OPENAI_MODEL_1', 'gpt-3.5-turbo');
define('CFG_OPENAI_TEMP_1', 0.7);
define('CFG_OPENAI_MAXTOKENS_1', 800);

/* DialogFlow */
define('CFG_DIALOGFLOW_PROJECTID', 'avib2b-dev');

/* MyAVI */
define('CFG_MYAVIAPI_URL', '');
define('CFG_MYAVIAPI_TEMP', 0.6);
define('CFG_MYAVIAPI_MAXTOKENS', 300);

/* SSO - Google */
$cfg_sso_google_clientid = 'dummy-client-id';
$cfg_sso_google_clientsecret = 'dummy-secret';

/* Records */
define('CFG_RECORDS_PARTSMS', 300000);
define('CFG_RECORDS_PATH', 'uploads/records/');
