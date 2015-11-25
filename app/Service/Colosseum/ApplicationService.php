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
class ApplicationService extends ColosseumService
{	

  /***********************************/
 /*									*/
/***********************************/
    public static function createOne($json_url, $postData)
    {
		$application = parent::createOne($json_url, $postData->application);
		$applicationComponents = [];
		foreach ($postData->applicationComponents as $key => $value) 
		{			
			$value->application = $application->id;
			$applicationComponents[] =  parent::createOne("/ac", $value);	
		}

		foreach ($postData->communications as $key => $value) 
		{		
			$communication = [
				"provider" => $applicationComponents[$value->provider]->id,
				"consumer" => $applicationComponents[$value->consumer]->id,
				"port" => 8080
			];

			$applicationComponents[] =  parent::createOne("/communication", $communication);	
		}	
		$postData->id = $application->id;
		return static::parseResult($application);
    }

	public static function updateOne($apiname,$val, $putData)
    {		
    	$url ="/$apiname/$val";
		$result = static::getResponse($url,'PUT', [ "name" => $putData->name ]);
		if($putData->commands)
		{
			foreach ($putData->commands as $key => $value) 
			{	

				$service =  RestController::getService($value->apiObject);
				call_user_func_array($service."::".$value->action, [$value->url,json_encode($value->params)]);
			}	

		}
		return static::parseResult($result);
    }

    public static function getOne($apiname,$val)
    {		
    	$url ="/$apiname/$val";
    	//GET APPLICATION BY ID
		$result = parent::getSimpleOne($apiname,$val);

		//GET ApplicationComponent BY application id
		$applicationComponents = parent::getSimpleAll("applicationComponent");
		$result->applicationComponents = [];		
		$result->applicationComponentsById = [];		
		foreach ($applicationComponents as $key => $value) 
		{
			if($result->id == $value->application)
			{
				$component = parent::getSimpleOne("component",$value->component);
				$value->components = [$component];
				$result->applicationComponentsById[$value->id] = $value;
				$result->applicationComponents[] = $value;
			}
		}

		//GET portReq BY application id
		$portReqs = parent::getSimpleAll("requiredPort");
		$result->portReqs = [];		

		foreach ($portReqs as $key => $value) 
		{
			if(array_key_exists ( $value->applicationComponent , $result->applicationComponentsById))
			{
				//$value->component = static::parseResults(static::getResponse("/lifecycleComponent/".$value->component,'GET', ''));
				$result->portReqs[$value->id] = $value;
			}
		}

		//GET portProv BY application id
		$portProvs = parent::getSimpleAll("providedPort");
		$result->portProvs = [];		
		foreach ($portProvs as $key => $value) 
		{
			if(array_key_exists( $value->applicationComponent , $result->applicationComponentsById))
			{
				//$value->component = static::parseResults(static::getResponse("/lifecycleComponent/".$value->component,'GET', ''));
				$result->portProvs[$value->id] = $value;
			}
		}

		//GET COMMUCICATION by ...
		$communications = parent::getSimpleAll("communication");
		$result->communications = [];
		foreach ($communications as $key => $value) 
		{
			if(isset($result->portReqs[$value->requiredPort]) && isset($result->portProvs[$value->providedPort]) )
			{

				//$communications = static::parseResults(static::getResponse("/communication",'GET', ''));
				$result->communications[] = $value;
			}
		}
		return static::parseResult($result);
    }
}