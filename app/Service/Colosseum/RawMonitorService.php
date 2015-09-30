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
class RawMonitorService extends ColosseumService
{	

  /***********************************/
 /*									*/
/***********************************/

  	public static function getOne($apiname,$val)
    {		
    	$url ="/$apiname/$val";
    	//GET VM BY ID
		$result = static::getSimpleOne($apiname,$val);

		//GET monitorInstance
		$array = [];		
		foreach ($result->monitorInstances as $key => $value) 
		{
			$temp = static::getSimpleOne("monitorInstance",$value);
			$result->sensorDescription = static::getSimpleOne("sensorDescription",$result->sensorDescription);
			$temp->metricName = $result->sensorDescription->metricName;
			$array[] = $temp;
		}
		$result->monitorInstances = $array;
		return $result;
    }
}