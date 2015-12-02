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
class ColosseumService extends RestService
{

	public static function getResponse($url, $method, $putData, $apiname)
	{

		$url = Tools::getApiInfo($apiname)['url'].$url;
		$result = null;	
		$var = null;
		if(isset($_SESSION['user']) || $url== "/login")
		{
			list($result, $var) = static::makeRequest($url,$method,$putData, $apiname);
			if($url!= "/login" && isset($_SESSION['user']) && floor($var/100) == 4)
			{
				static::reLogin();
				list($result, $var) = static::makeRequest($url,$method,$putData, $apiname);
			}
			http_response_code(intval($var));
		}
		else
		{
			http_response_code(401);
		}
		return json_decode($result);
	}

	public static function getHeaderRequest()
	{
		$logInfo = static::getLoginInfo();
		if(isset($logInfo) && isset($logInfo->token))
		{
			return 	["Content-type: application/json","X-Auth-Token: $logInfo->token","X-Auth-UserId: $logInfo->userId", "X-Tenant: $logInfo->tenant"];
		}
		return 	["Content-type: application/json"];
	}

	public static function login($user)
	{

		$email = (isset( $user->email))? $user->email:null;
		$password = (isset( $user->password))? $user->password:null;
		$tenant = (isset( $user->tenant))? $user->tenant:null;

		$url = Tools::getApiInfo('login')['url']."/login";
		$data = array("email" => $email , "password" => $password, "tenant" => $tenant);
		$method ='POST';
		list($result, $code) = static::makeRequest($url,$method,$data, "login");
		
				//return json_decode($result);
		http_response_code(intval($code));	
		if($code=="400")
		{
			if(Tools::isJson($result))
			{
				$result = json_decode($result);
				return $result->_empty_[0];
			}
		}
	
		$apiInfo = json_decode($result);
		$user = (object) array();
		$user->email = $email;
		$user->password = $password;
		$user->tenant = $tenant;
		if($apiInfo != null)
		{
			$user->createdOn = $apiInfo->createdOn;
			$user->expiresAt = $apiInfo->expiresAt;
			$user->token = $apiInfo->token;
			$user->userId = $apiInfo->userId;
		}	

		$_SESSION['user'] = $user;
		return $user;
	}

	public static function reLogin()
	{
		if(isset($_SESSION['user']))
		{
			return static::login($_SESSION['user']);
		}
		else
		{
			http_response_code(401);
			return array("message"=>"not logged");
		}
	}

	public static function getLoginInfo()
	{
		return (isset($_SESSION['user']))? $_SESSION['user'] : null;
	}	

	public static function parseResult($value)
    {
		if(isset($value))
		{
			if(isset($value->link))
			{
				$temp = $value->link[0]->href;
				substr( $temp , strrpos( $temp , "/" )+1);
				$id = substr( $temp , strrpos( $temp , "/" )+1);
				$value->id = $id;

				//To have Id as the first element
				$x = (array)$value;
				$x = ['id' => $id] + $x;
				$value = (object)$x;

				unset($value->link);
			}
		}
		return $value;
	}

	public static function deparseResult($value)
    {
		unset($value->id);
		return $value;
	}	
}