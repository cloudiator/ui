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
 
class RestRouter
{
	private static $CONTROLLER = "RestController";	
	
    public static function getController($apiname)
    {
    	$temp = ucfirst ($apiname)."Controller";
    	if(class_exists ($temp))
    	{
    		return $temp;
    	}
    	elseif(isset($GLOBALS['currentapi']["controller"]))
		{
			return $GLOBALS['currentapi']["controller"];
		}
		else
		{
			return static::$CONTROLLER; 
		}
    }

	public static function routes($apiname,$routes,$request_type)
	{
		$controller = static::getController($apiname);

		Route::post('login', 		$controller.'::login',		$routes,$request_type);
		Route::get('login', 		$controller.'::isLogged',		$routes,$request_type);
		Route::any('logout', 		$controller.'::logout',		$routes,$request_type);
		Route::get('{}/options', 	$controller.'::getOptionsByEntityName',	$routes,$request_type);
		Route::post('options', 		$controller.'::getOptions',	$routes,$request_type);
		Route::post('{}', 			$controller.'::createOne',	$routes,$request_type);
		Route::delete('{}/{}', 		$controller.'::deleteOne',	$routes,$request_type);
		Route::put('{}/{}', 		$controller.'::updateOne',	$routes,$request_type);
		Route::get('{}', 			$controller.'::getAll',		$routes,$request_type);
		Route::get('{}/lazy', 		$controller.'::getSimpleAll',		$routes,$request_type);
		Route::get('{}/{}', 		$controller.'::getOne',		$routes,$request_type);
	}
}