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
//require_once __DIR__.'/../Service/ColosseumService.php';
 
class ColosseumController extends RestController
{	
	public static $SERVICE = 'ColosseumService';

	public static function login()
	{
		$service = static::getService('login');

		if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			return $service::relogin();
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$putData =  static::getRequestContent();
			return $service::login($putData);
		}
	}
}