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
crudControllers.controller('VMDetailCtrl', function($controller,$rootScope,$scope,$location,$routeParams,ajaxFactory)
{
	$routeParams.apiname = "virtualMachine";
	angular.extend(this, $controller('AbstractDetailCtrl', {$rootScope,$scope,$location,$routeParams,ajaxFactory}));

	$scope.init = function()
	{

		$scope.chart = { name: 'charts.html', url: 'public/partials/charts.html'};
		
		$scope.url = $routeParams.apiname+"/"+$routeParams.apiid+"/";

		//get the VM
		ajaxFactory.get($scope.url).then(function(data)
		{
			$scope.item =  data;
		});
		$scope.redirectUrl = $routeParams.apiname+"/";
	}
	$scope.init();
});


crudControllers.controller('MonitorInstanceDetailCtrl', function($controller,$rootScope,$scope,$location,$routeParams,ajaxFactory)
{
	$routeParams.apiname = "monitorInstance";
	angular.extend(this, $controller('AbstractDetailCtrl', {$rootScope,$scope,$location,$routeParams,ajaxFactory}));

	$scope.init = function()
	{

		$scope.chart = { name: 'charts.html', url: 'public/partials/charts.html'};
		
		$scope.url = $routeParams.apiname+"/"+$routeParams.apiid+"/";
	
		
		//get the VM
		ajaxFactory.get($scope.url).then(function(data)
		{
			$scope.item =  data;
		});
		$scope.redirectUrl = $routeParams.apiname+"/";
	}
	$scope.init();
});