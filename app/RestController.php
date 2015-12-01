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

class RestController
{
  /***********************************/
 /*									*/
/***********************************/

	private static $SERVICE = "RestService";	

	//Return the Service of the same name as the API, $SERVICE value by default
    public static function getService($apiname = null) //ok
    {
		//$service = static::$SERVICE;
		if(isset($apiname))
		{
	    	if(class_exists (ucfirst($apiname)."Service"))
	    	{
	    		return ucfirst($apiname)."Service";
	    	}

	    	foreach($GLOBALS['config']['apisObjects'] as $key => $value)
	    	{
	    		if($key == $apiname)
	    		{
	    			//return $value["api"];
	    			$controller =  $GLOBALS['config']['apis'][$value["api"]]["controller"];
	    			$service = $controller::$SERVICE;
	    			return $service;
	    		}
	    	}

		}
		return static::$SERVICE;
    }

  /***********************************/
 /*									*/
/***********************************/
	
	
	public static function login()
	{
		$service = static::getService("login");
		$putData =  static::getRequestContent();
		$service::login($putData->email,$putData->password);
		return "";
	}

	
	public static function info()
	{

		header('Content-Type: application/json');
		return $GLOBALS['config'];
	}


	public static function isLogged()
	{
		$service = static::getService("login");
		$putData =  static::getRequestContent();
		//TODO get login and use the code number (200 ok, 401 ko)
		//if()
		//$service::login($putData->email,$putData->password);
		return true;
	}
	
	public static function logout()
	{
		$json_url = '/logout';
		unset($_SESSION['user']);
		return "logout";
	}

    public static function getSimpleAll($apiname)
    {
		$service = static::getService($apiname);	
		$result = $service::getSimpleAll($apiname);
		return $result;
    }

    public static function getAll($apiname)
    {
		$service = static::getService($apiname);		
		$result = $service::getAll($apiname);
		return $result;
    }

    public static function getOne($apiname,$val)
    {
		$service = static::getService($apiname);
		$result = $service::getOne($apiname,$val);
		return $result;
    }

    public static function deleteOne($apiname,$val)
    {	
		$service = static::getService($apiname);
		$result =  $service::deleteOne($apiname,$val);
		return $result;
    }

    public static function createOne($apiname)
    {
		$service = static::getService($apiname);
		$result =  $service::createOne($apiname, static::getRequestContent());		
		return $result;
    }

    public static function updateOne( $apiname,$val)
    {
		$service = static::getService($apiname);
		$result =  $service::updateOne($apiname, $val, static::getRequestContent());
		return $result;
    }

    public static function getOptions()
    {		
		$service = static::$SERVICE;
		$options =  static::getRequestContent();	
		$ret = [];
		foreach($options as $option)
		{			
			$ret[$option] = static::getSimpleAll($option);
		}
		return $ret;
    }
   public static function getOptionsByEntityName($entityname)
    {		
		$service = static::$SERVICE;
		return $service::getOptions($entityname);
    }


  /***********************************/
 /*									*/
/***********************************/


    public static function getRequestContent()
    {
		$ret = file_get_contents( 'php://input');
		if($ret)
		{
			return json_decode($ret)->params;
		}
		else
		{
			return array();
		}
	}
/*	public static function getOnlyWith($array,$val,$fk)
	{
		$ret = array();
		if($array!=null)
		{			
			$array = $array;
			foreach($array as $value)
			{
				if($value->$fk == $val)
				{
					$ret[] = $value;
				}
			}
		}
		return $ret;	
	}
	*/
  /***********************************/
 /*									*/
/***********************************/

}