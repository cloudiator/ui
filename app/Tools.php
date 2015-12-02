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
class Tools
{


    public static function convertToFields($currents,$currentApi)
    {	
		$items = [];
    	foreach ($currents as $key => $value) 
		{
			$items[] = static::convertToField($value,$currentApi);	
		}
		return $items;
    }

    public static function convertToField($current,$currentApi)
    {	
		$item = new stdClass();
		$item->id = $current->id;

		if( isset($GLOBALS['config']['apisObjects'][$currentApi]["tostring"]) )
		{
			$tostring = $GLOBALS['config']['apisObjects'][$currentApi]["tostring"];
		}
		else
		{
			$tostring = "id";
		}

		$item->name = $current->$tostring;
		$item->apiName = $currentApi;

		return $item;
    }

    public static function getKeyValueArray($array)
    {
    	$ret = [];

		foreach ($array as $key => $value) 
		{
			$ret[$value->id] = $value;
		}

    	return $ret;
    }

	public static function getById($array,$id)
    {
    	foreach ($array as $key => $value) 
    	{
    		if($value->id == $id)
    		{
    			return $value;
    		}
    	}

    	return null;
    }

	public static function getApiEntityName($localEntityName)
	{
    	$entityInfo = Tools::getEntityInfo($localEntityName);

		if(isset($entityInfo))
		{
			return (isset($entityInfo['entityName'])) ? $entityInfo['entityName'] :$localEntityName;
		}

		return null;
	}

    public static function getCurrentAPI($apiname) //ok
    {
		if(isset($GLOBALS['config']['apisObjects'][$apiname]))
    	{
			$GLOBALS['currentobject'] = $GLOBALS['config']['apisObjects'][$apiname];
			//TODO DO NOT TROW ERROR HERE BUT WHEN TRY TO USE IT: API does not exist
			if(isset($GLOBALS['config']['apis'][$GLOBALS['currentobject']["api"]]))
			{
				return $GLOBALS['config']['apis'][$GLOBALS['currentobject']["api"]];
				//TODO DO NOT TROW ERROR HERE BUT WHEN TRY TO USE IT: AAPI Object does not exist
			}
		}
    }

    public static function getEntityInfo($entityName) //ok
    {
		if(isset($GLOBALS['config']['apisObjects'][$entityName]))
    	{
			return $GLOBALS['config']['apisObjects'][$entityName];
		}
		return null;
    }

    public static function getApiInfo($entityName) //ok
    {
    	$entityInfo = Tools::getEntityInfo($entityName);
		if(isset($entityInfo))
    	{
			if(isset($GLOBALS['config']['apis'][$entityInfo["api"]]))
			{
				return $GLOBALS['config']['apis'][$entityInfo["api"]];
			}
		}
		return null;
    }

	public static function isJson($string) 
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}