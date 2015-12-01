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
 * @author Ahmed Zarioh (ahmed.zarioh@gmail.com)
 */
class Dispatcher
{
	private static $ROUTER = "RestRouter";	
	
	//Return the Route from the configuration file, $ROUTER value by default
    public static function getRouter() //ok
    {
		if(isset($GLOBALS['currentapi']["router"]))
		{
			return $GLOBALS['currentapi']["router"];
		}
		else
		{
			return static::$ROUTER; 
		}
    }

    public static function getCurrentAPI($apiname) //ok
    {
		if(isset($GLOBALS['config']['apisObjects'][$apiname]))
    	{
			$GLOBALS['currentobject'] = $GLOBALS['config']['apisObjects'][$apiname];
			//DO NOT TROW ERROR HERE BUT WHEN TRY TO USE IT: API does not exist
			if(isset($GLOBALS['config']['apis'][$GLOBALS['currentobject']["api"]]))
			{
				$GLOBALS['currentapi'] = $GLOBALS['config']['apis'][$GLOBALS['currentobject']["api"]];
				//DO NOT TROW ERROR HERE BUT WHEN TRY TO USE IT: AAPI Object does not exist
			}
		}
    }

	public static function dispatch($routes,$request_type)
	{
		$controller_route = $routes[0];
		switch ($controller_route) 
        {
                case "": 
                    $GLOBALS["return"] = null;

					//client side
					$GLOBALS['client']['apisObjects'] = json_decode(file_get_contents("config/apisObjects.json"), true);
					$GLOBALS['client']['self'] = $GLOBALS['path']['base'];
					$GLOBALS['client']['nav'] = json_decode(file_get_contents("config/nav.json"));
					$GLOBALS['client']['enums'] = json_decode(file_get_contents("config/enums.json"), true);
					$GLOBALS['client']['env'] = "dev";

                    //$GLOBALS["return"] = file_get_contents(__DIR__.'/View/main.php');
                    include __DIR__.'/main.php';
                    break;
                default : 
					$apiname = $routes[1];
					static::getCurrentAPI($apiname);
					$router = static::getRouter();
					array_splice($routes,0,1);
					$router::routes($apiname,$routes,$request_type);
        }
	}
}