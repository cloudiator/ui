
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

/* Controllers */
crudControllers.controller('ChartsCtrl', function($scope,$interval,$http,ajaxFactory)
{
	$scope.url = "kairosapi/charts";

	$scope.init2 = function(id,metricName = null,apiEndpoint=null) 
	{
		monitorInstance = {};
		monitorInstance.id = id;
		monitorInstance.metricName = metricName;
		monitorInstance.apiEndpoint = apiEndpoint;
		$scope.init(monitorInstance);
	}

	$scope.init = function(monitorInstance) 
	{

			$scope.wait = false;
		$scope.graphDetails = {};
		$scope.graphDetails.monitorInstance = monitorInstance.id;
		
		$scope.graphDetails.metricName = monitorInstance.metricName;

		//TODO remove this informations
		//$scope.graphDetails.sensorClassName = monitorInstance.monitor.sensorDescription.className;

		//TODO remove these hard coded information
		$scope.graphDetails.interval = {};
		$scope.graphDetails.interval.value = 1;
		$scope.graphDetails.interval.unit = "MINUTES";
		$scope.graphDetails.start_relative = {};
		$scope.graphDetails.start_relative.value = 1;
		$scope.graphDetails.apiEndpoint = monitorInstance.apiEndpoint;

		switch($scope.graphDetails.interval.unit) 
		{
		    case "SECONDS":
			    $scope.graphDetails.start_relative.unit = "MINUTES";
			    break;
		    case "MINUTES":
				$scope.graphDetails.start_relative.unit = "HOURS";
		        break;
		    case "HOURS":
				$scope.graphDetails.start_relative.unit = "DAYS";
		        break;
		    case "DAYS":
				$scope.graphDetails.start_relative.unit = "MONTHS";
		        break;
		    case "MONTHS":
				$scope.graphDetails.start_relative.unit = "YEARS";
		        break;
		    default:
				$scope.graphDetails.start_relative.unit = "HOURS";
		}
		$scope.change();
	}
	
	$scope.change = function() 
	{
		//TODO add a protection like a synchronized thread (a variable that is false durring the ajax and would hide/enabled the inputs)
		if($scope.wait == false)
		{
			$scope.wait = true;
			$scope.error = false;
			
			//to change to "pure" angularjs code
			var graph = $("#graph-num-"+$scope.k);
			$scope.canvW = graph.find("canvas").width();
			$scope.canvH = graph.find("canvas").height();

			//$scope.azara = $scope.graphDetails;
			debug("graphDetails :",$scope.graphDetails);
			ajaxFactory.post("charts",$scope.graphDetails).then(function(data, status, headers, config)
			{		
				//$scope.azara = data;
				if(data &&data.queries)
				{
					$scope.data = getInfoChart(data.queries);
				}
				else
				{

				}
				$scope.wait = false;
			},
			function(data, status, headers, config) 
			{
				$scope.wait = false;
				$scope.error = true;
			});	
		}
	};

	$scope.interval = $interval($scope.change,10000);

	$scope.$on("$destroy", function(){
		$interval.cancel($scope.interval);
	});
});