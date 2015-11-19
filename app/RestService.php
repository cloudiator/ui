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
class RestService
{	
  /***********************************/
 /*									*/
/***********************************/



	// return a list of all foreign key names for an apiObject
    private static function getFKList($fieldsList)
    {
		$fkList = [];
		foreach ($fieldsList as $key => $value) 
		{
			if($value=="apiObject" )
			{
				$fkList[$key]=$key;
			}
			elseif ( isset($value["type"]) && $value["type"]=="apiObject")
			{
				$fkList[$key]=$value["name"];
			}
			elseif ( isset($value["type"]) && $value["type"]=="internalObject")
			{
				//TODO concat arrays
				 array_merge($fkList, static::getFKList($value["content"]));
			}
		}
		return $fkList;
    }

	// return a list of list of forein key objects
    public static function getCurrentObjectOptions()
    {
    	if(isset($GLOBALS['currentobject']))
    	{	
			$fkList = static::getFKList($GLOBALS['currentobject']["form"]);
			//for all the apiobject get the list
			$apiObjects = [];

			foreach ($fkList as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						if(!isset($apiObjects[$value2]))
						{
							$apiObjects[$value2] = [];
							$temp = static::getSimpleAll($value2);
							if(isset($temp))
							{
								foreach ($temp  as $k => $val) 
								{
									$apiObjects[$value2][$val->id] = $val;				
								}
							}
						}
					}
				}
				elseif(!isset($apiObjects[$value]))
				{
					$apiObjects[$value] = [];
					$temp = static::getSimpleAll($value);
					foreach ($temp  as $k => $val) 
					{
						$apiObjects[$value][$val->id] = $val;				
					}	
				}		
			}
			return $apiObjects;
    	}
    	else
    	{
    		return [];
    	}
	}
  /***********************************/
 /*									*/ 
/***********************************/  
    public static function login($user)
	{
		return "ok";
	}

	public static function logout()
	{
		return "ok";
	}

	// return a list of list of forein key objects
    public static function getOptions($entityname)
    {
		$entityInfo =Tools::getEntityInfo($entityname);
		$fkList = static::getFKList($entityInfo["form"]);
		//for all the apiobject get the list
		$apiObjects = [];
		foreach ($fkList as $key => $value) 
		{
			if(!isset($apiObjects[$value]))
			{
				$apiObjects[$value] = [];
				$temp = static::getSimpleAll($value);
				foreach ($temp  as $k => $val) 
				{
					$apiObjects[$value][$val->id] = $val;				
				}	
			}		
		}
		return $apiObjects;
	}

    public static function getAll($apiname)
    {
    	$result = static::getSimpleAll($apiname);
    	if(count($result))
    	{
			$apiObjects = static::getCurrentObjectOptions();
			$fkList = static::getFKList($GLOBALS['currentobject']["form"]);
			foreach ($result as $key1 => $value1) 
			{
				foreach ($fkList as $key2 => $value2) 
				{
					if(isset($value1->$key2)  && 
						!(isset($GLOBALS['config']['apisObjects'][$apiname]["form"][$key2]["isArray"]) && 
							$GLOBALS['config']['apisObjects'][$apiname]["form"][$key2]["isArray"]==true))
					{
						if(is_array($value2))
						{
							$cont = true;


							foreach ($value2 as $key3 => $value3) 
							{
								if($cont && isset($apiObjects[$value3][$value1->$key2]))
								{
									$current = $apiObjects[$value3][$value1->$key2];
									$result[$key1]->$key2 = Tools::convertToField($current,$value3);
									$cont = false;
								}
							}
						}
						else
						{
							$current = $apiObjects[$value2][$value1->$key2];
							$result[$key1]->$key2 = Tools::convertToField($current,$value2);
						}
					}
				}
			}
    	}
		return static::parseResults($result);
    }


     public static function getOne($apiname,$val)
    {
    	$result = static::getSimpleOne($apiname,$val);
		$fkList = static::getFKList($GLOBALS['currentobject']["form"]);
		foreach ($fkList as $key2 => $value2) 
		{
			if(isset($result->$key2)  && 
				!(isset($GLOBALS['config']['apisObjects'][$apiname]["form"][$key2]["isArray"]) && 
					$GLOBALS['config']['apisObjects'][$apiname]["form"][$key2]["isArray"]==true))
			{
				$index = $result->$key2;
				if(is_array($value2))
				{
					$cont = true;
					foreach ($value2 as $key3 => $value3) 
					{
						if($cont)
						{
							$current = static::getSimpleOne($value3,$index);
							if($current)
							{
								$result->$key2 = Tools::convertToField($current,$value3);
								$cont = false;
							}
						}
					}
				}
				else
				{
					$current = static::getSimpleOne($value2,$index);
					$result->$key2 = Tools::convertToField($current,$value2);
				}
			}
		}
		return static::parseResult($result);
    }

    public static function getSimpleAll($apiname)
    {
		$entityName = Tools::getApiEntityName($apiname);		
    	$url ="/$entityName";
		$result = static::getResponse($url,'GET', '', $apiname);
		return static::parseResults($result);
    }


    public static function getSimpleOne($apiname,$val)
    {	
		$entityName = Tools::getApiEntityName($apiname);		
    	$url ="/$entityName/$val";
		$result = static::getResponse($url,'GET', '', $apiname);
		return static::parseResult($result);
    }

    public static function deleteOne($apiname,$val)
    {		
		$entityName = Tools::getApiEntityName($apiname);	
    	$url ="/$entityName/$val";
		$result = static::getResponse($url,'DELETE', '', $apiname);
		return static::parseResults($result);
    }

    public static function createOne($apiname, $putData)
    {
		$entityName = Tools::getApiEntityName($apiname);	
    	$url ="/$entityName";
		$result = static::getResponse($url,'POST', $putData, $apiname);		
		return static::parseResult($result);
    }

	public static function updateOne($apiname,$val, $putData)
    {	
		$entityName = Tools::getApiEntityName($apiname);		
    	$url ="/$entityName/$val";
		$result = static::getResponse($url,'PUT', $putData,$apiname);
		return static::parseResult($result);
    }

  /***********************************/
 /*									*/ 
/***********************************/  

	public static function getResponse($url,$method,$putData,$apiname)
	{
		$result = null;
		$url = Tools::getApiInfo($apiname)['url'].$url;
		list($result, $var) = static::makeRequest($url,$method,$putData);
		http_response_code(intval($var));
		return json_decode($result);
	}

	public static function makeRequest($url, $method, $data)
	{ 
		$ch = curl_init();
		switch ($method)
		{
			case 'DELETE' :
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				break;
			case 'GET' :
				if ($data)
				{
		            $url = sprintf("%s?%s", $url, json_encode($data));
					//$url .= '?' . http_build_query($data);
				}
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				break;
			case 'PUT' :
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				break;
	 	}
		curl_setopt($ch, CURLOPT_HTTPHEADER, static::getHeaderRequest());
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 8); //timeout in seconds
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$api_response = curl_exec($ch);
		$api_response_info = curl_getinfo($ch);
		curl_close($ch);
	 
		// Here we separate the Response Header from the Response Body
		$header = trim(substr($api_response, 0, $api_response_info['header_size']));
		$result = substr($api_response, $api_response_info['header_size']);
		$code = $api_response_info['http_code'];
		return array($result, $code);
	}

  /***********************************/
 /*									*/
/***********************************/
	public static function getHeaderRequest()
	{
		return 	["Content-type: application/json"];
	}
		
	/*public static function getApiUrl()
	{
		return $GLOBALS['currentapi']['url'];
	}*/
	



	
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

	public static function parseResults($array)
    {
		if($array!=null)
		{			
			foreach($array as &$value)
			{
				$value = static::parseResult($value);
			}
		}
		return $array;	
	}

	public static function parseResult($value)
    {
		return $value;
	}

	public static function deparseResults($array)
    {
		if($array!=null)
		{			
			foreach($array as &$value)
			{
				$value = static::deparseResult($value);
			}
		}
		return $array;	
	}
	
	public static function deparseResult($value)
    {
		return $value;
	}	


}