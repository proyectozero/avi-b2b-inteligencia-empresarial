<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

/*
* MVC avi-worker - Brain Controller
*/

//config & db connections
require_once 'config.php';
require_once 'conn.php';
require_once 'conn_dw.php';

//users class
require_once('class/users/auth.php');
require_once('class/users/logout.php');
require_once('class/users/usersinfo.php');

//extransl libs
include 'libs/parsedown/Parsedown.php';

//MVC avi-brain Brain Models
require_once ('class/avi-brain/intent_detector.php');
require_once ('class/avi-brain/input_processor.php');
require_once ('class/avi-brain/ask_gpt.php');

//MVC avi-brain Output Models
require_once ('class/avi-brain/output_reportes.php');
require_once ('class/avi-brain/output_openai.php');
require_once ('class/avi-brain/output_default.php');
require_once ('class/avi-brain/output_myavi.php');
require_once ('class/avi-brain/output_legal.php');

//MVC avi-bi Prompts Models
require_once ('class/avi-bi/prompt_sql_data.php');
require_once ('class/avi-bi/prompt_sql_charts.php');
require_once ('class/avi-bi/prompt_recomendacion.php');
require_once ('class/avi-bi/prompt_conversational.php');
require_once ('class/avi-bi/prompt_analisis.php');

//MVC avi-bi Data Models
require_once ('class/avi-bi/data_exec.php');
require_once ('class/avi-bi/data_fetcher.php');
require_once ('class/avi-bi/data_cleaner.php');
require_once ('class/avi-bi/data_chart.php');

//MVC myavi Models
require_once ('class/myavi/api.php');
require_once ('class/avi-legal/api.php');

//MVC avi-bi Views
require_once 'views/avi-bi/html_accordion.php';
require_once 'views/avi-bi/html_datatable.php';
require_once 'views/avi-bi/html_bullets.php';
require_once 'views/avi-bi/html_chartjs.php';
require_once 'views/avi-bi/html_googlecharts.php';

$UsersId = null;
if (isset($_GET['UsersId'])) {
    $UsersId = $_GET['UsersId'];
}

// recordset - users info
$users      = class_usersinfo($UsersId, $conn);
$usersinfo  = $users['info'];

//Global Vars
$UsersId = null;
if (isset($usersinfo['UsersId'])) {
    $UsersId = $usersinfo['UsersId'];
}

$SessionId = null;
if (isset($usersinfo['SessionId'])) {
    $SessionId = $usersinfo['SessionId'];
}

$users_debug = 0;
if (isset($usersinfo['Debug'])) {
    $users_debug = $usersinfo['Debug'];
}

//Global Vars
$debug = $users_debug;
if (isset($_GET['debug'])) {
    $debug = $_GET['debug'];
}

$method = 'html';
if (isset($_GET['method'])) {
    $method = $_GET['method'];
}

$message = null;
if (isset($data['message'])) {
    $message = $data['message'];
}

$personality = null;
if (isset($usersinfo['Personality'])) {
    $personality = $usersinfo['Personality'];
}

$prompt = $message;
if (isset($_GET['prompt'])) {
    $prompt = $_GET['prompt'];
}

$fault['code']  = 0;
$response       = null;
$arr_processor  = null;

if ($users['SessionId']) {
    if ($prompt) {
        $arr_processor = class_inputProcessor($prompt, $personality, $UsersId, $SessionId, $conn, $conn_dw, $debug);
        if ($arr_processor['response']) {
            $response   = $arr_processor['response'];
        }else{
            $fault = array('code' => 1, 'message' => 'Error: No hay respuesta por parte de AVI-Brain');
        }
    }else{
        $fault = array('code' => 2, 'message' => 'Error: No hay una pregunta válida o el prompt está vacio');
    }
}else{
    $fault = array('code' => 3, 'message' => 'Error: No hay una sesión de usuario válida');
}

// fault error
if ($fault['code']) {
    echo "<pre>";
    print_r($fault);
    exit;
}

$debug_msg = null;
if ($debug) {
    $debug_msg = $arr_processor;
}

//output format
if ($method == 'array') {
    $array_results = $arr_processor;
    echo "<pre>";
    print_r($array_results);
}

if ($method == 'json') {
    $json_results = json_encode(['reply' => $arr_processor]);
    header('Content-Type: application/json');
    echo $json_results;
}

if ($method == 'html') {

    //html output for AVI-BI

    if ($response['type']=='report') {

        $reports = $response['report'];
        $html = class_htmlAccordion(
            $response['title'],
            $debug_msg,
            $response['response'],
            $reports['data'],
            $reports['datachart'],
            $reports['analysis'],
            $reports['recommendation'],
            null,
            null,
        );
    }

    //html output for AVI-Legal
    if ($response['type']=='legal') {
        $html = class_htmlAccordion(
            $response['title'],
            $debug_msg,
            $response['response'],
            null,
            null,
            null,
            null,
            $response['basis'],
            $response['cases'],
        );
    }

    //html output for AVI-Talk
    if ($response['type']=='conversation') {
        $html = class_htmlAccordion(
            $response['title'],
            $debug_msg,
            $response['response'],
            null,
            null,
            null,
            null,
            null,
            null,
        );
    }

    echo $html;
}