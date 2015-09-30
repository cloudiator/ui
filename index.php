<?php
/*
 * Copyright 2015 be.wan s.p.r.l.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 */

/*
 * @author Ahmed Zarioh <ahmed.zarioh@gmail.com>
 */ 
 
//for login 
session_start();

require_once __DIR__.'/app/Dispatcher.php';
require_once __DIR__.'/app/RestRouter.php';
require_once __DIR__.'/app/RestController.php';
require_once __DIR__.'/app/RestService.php';
require_once __DIR__.'/app/Route.php';
require_once __DIR__.'/app/Tools.php';

include_all_php(__DIR__.'/app/Router');
include_all_php(__DIR__.'/app/Controller');
include_all_php(__DIR__.'/app/Service');

//This method include all php file from a folder name
function include_all_php($folder)
{
    foreach (glob("{$folder}/*.php") as $filename)
    {
        require_once $filename;
    }

    foreach (glob("{$folder}/*") as $foldername)
    {
    	if(is_dir($foldername))
    	{
			include_all_php($foldername);
    	}
    }
}

function parse_path() 
{
	$path = array();
	if (isset($_SERVER['REQUEST_URI'])) 
	{
		$server_request = explode('?', $_SERVER['REQUEST_URI']);
		
		//get the content folder of the dispatcher
		$path['base'] = rtrim(dirname($_SERVER['PHP_SELF']), '\/');

		//get urls path_varaiables 
		$call = utf8_decode(substr(urldecode($server_request[0]), strlen($path['base']) + 1));
		$path['route_parameters'] = explode('/', $call);
		
		// get parameters
		$path['request_parameters'] = array();
		if(count($server_request)>1)
		{
			$params = explode('&', utf8_decode(urldecode($server_request[1])));
			foreach ($params as $param) 
			{
				$var = explode('=', $param);
				$path['request_parameters'][$var[0]] = $var[1];
			}
		}
	}
	return $path;
}

$GLOBALS['path'] = parse_path();

if(file_exists("/etc/paasage/executionware-ui-apis.json"))
	$GLOBALS['config']['apis'] = json_decode(file_get_contents("/etc/paasage/executionware-ui-apis.json"), true);
else
	$GLOBALS['config']['apis'] = json_decode(file_get_contents("executionware-ui-apis.json"), true);
$GLOBALS['config']['metrics'] = json_decode(file_get_contents("config/metrics.json"), true);
$GLOBALS['config']['apisObjects'] = json_decode(file_get_contents("config/apisObjects.json"), true);

$GLOBALS['routes'] = $GLOBALS['path']['route_parameters'];

Dispatcher::dispatch($GLOBALS['routes'],$_SERVER['REQUEST_METHOD']);
if(isset($GLOBALS["return"]))
{
	echo json_encode($GLOBALS["return"]);
}
else
{
	echo "error return";
}