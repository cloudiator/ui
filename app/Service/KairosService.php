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
class KairosService extends RestService
{
	
	private static function getAggregator($inputData)
	{	
		return "avg";
		/*
			$metrics = $GLOBALS['config']['metrics'][$inputData->sensorClassName];
			$metrics = json_decode( json_encode($metrics));
			$aggregator = $metrics->aggregator->name;
		*/
	}
	public static function getChart($chartInfo)
	{	

		$aggregatorName = static::getAggregator($chartInfo);

		$metric = array(
			"name" => isset($chartInfo->apiEndpoint)?$chartInfo->metricName:"aggregation",
			"tags" => array("monitorinstance" => $chartInfo->monitorInstance),
			"aggregators" => array(
				array(
					"name" => $aggregatorName,
					"align_sampling" => true,
					"sampling" => array(
					"value" => $chartInfo->interval->value,
					"unit" => $chartInfo->interval->unit
					)
				)
			)
		);

		if(isset($chartInfo->apiEndpoint))
		{
			$url = "http://".$chartInfo->apiEndpoint.":8080/api/v1/datapoints/query";
		}
		else
		{	
			$url =  $GLOBALS['config']['apis']['kairosapi']['url'];
		}

		$option = array(
			'metrics' => [$metric],
			'cache_time' => 0,
			'start_relative' => array(
				"value" => $chartInfo->start_relative->value,
				"unit"  => $chartInfo->start_relative->unit
		  	)
		);
		list($result,$code) = RestService::makeRequest($url,'POST',$option);
		return json_decode($result);
	}
}