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
class VirtualMachineService extends ColosseumService
{	

  /***********************************/
 /*									*/
/***********************************/

    public static function getOne($apiname,$val)
    {		
    	$url ="/$apiname/$val";
    	//GET VM BY ID
		$result = parent::getOne($apiname,$val);

		//GET monitorInstance
		$result->monitorInstances = [];		
		//$result->monitorInstancesById = [];	
		$monitorInstances = parent::getSimpleAll("monitorInstance");
		foreach ($monitorInstances as $key => $value) 
		{
			if($result->id == $value->virtualMachine)
			{
				
				//$result->monitorInstancesById[$value->id] = $value;
				$result->monitorInstances[] = $value;
			}
			
		}

		if(count($result->monitorInstances)>0)	
		{
			//"rawMonitor","composedMonitor","constantMonitor"

			$rawMonitors = parent::getSimpleAll("rawMonitor");
			$composedMonitors = parent::getSimpleAll("composedMonitor");
			$constantMonitors = parent::getSimpleAll("constantMonitor");
			$sensorDescriptions = parent::getSimpleAll("sensorDescription");

			$rawMonitorsById = Tools::getKeyValueArray($rawMonitors);
			$composedMonitorsById = Tools::getKeyValueArray($composedMonitors);
			$constantMonitorsById = Tools::getKeyValueArray($constantMonitors);
			$sensorDescriptionsById = Tools::getKeyValueArray($sensorDescriptions);
			
			foreach ($result->monitorInstances as $key => $value) 
			{

				$cont = true;
				if(isset($rawMonitorsById[$value->monitor]))
				{
					$value->monitor = $rawMonitorsById[$value->monitor];
					$value->monitor->apiObjectType = "rawMonitor";
					if(isset($sensorDescriptionsById[$value->monitor->sensorDescription]))
					{
						$value->monitor->sensorDescription = $sensorDescriptionsById[$value->monitor->sensorDescription];
						$value->metricName = $value->monitor->sensorDescription->metricName;
						$cont=false;
					}
				}
				else if(isset($composedMonitorsById[$value->monitor]))
				{
					$value->metricName = [];
					$value->monitor = $composedMonitorsById[$value->monitor];
					$value->monitor->apiObjectType = "composedMonitor";
					$tempMonitors = [];
					foreach ($value->monitor->monitors as $k => $v) 
					{
						if(isset($rawMonitorsById[$v]))
						{
							$temp = $rawMonitorsById[$v];
							if(isset($sensorDescriptionsById[$temp->sensorDescription]))
							{
								$temp->sensorDescription = $sensorDescriptionsById[$temp->sensorDescription];
								$value->metricName[] = $temp->sensorDescription->metricName;
								$cont=false;
								//kairosdb.datastore.query_time
							}
							$tempMonitors[] = $temp;
						}
					}
					$value->monitor->monitors = $tempMonitors;
				}
			}
		}
		return static::parseResult($result);
    }
}