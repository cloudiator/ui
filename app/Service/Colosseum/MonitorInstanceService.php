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
class MonitorInstanceService extends ColosseumService
{	

  /***********************************/
 /*									*/
/***********************************/

    public static function getOne($apiname,$val)
    {		
    	//GET MonitorInstance BY ID
		$monitorInstance = static::parseResult(parent::getOne($apiname,$val));
		
		if($monitorInstance->monitor->apiName=="rawMonitor")
		{
			$rawMonitor = static::parseResults(static::getSimpleOne("rawMonitor",$monitorInstance->monitor->id));
			$sensorDescription = static::parseResults(static::getSimpleOne("sensorDescription",$rawMonitor->sensorDescription));
			$monitorInstance->metricName = $sensorDescription->metricName;
		}
		elseif($monitorInstance->monitor->apiName=="composedMonitor")
		{
			$monitorInstance->metricName = "aggregation";
		}
		elseif($monitorInstance->monitor->apiName=="constantMonitor")
		{
			//TODO ...
		}
		return static::parseResult($monitorInstance);
    }
}