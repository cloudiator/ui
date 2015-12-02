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
crudControllers.controller('AbstractDetailCtrl', function($rootScope,$scope,$location,$routeParams,ajaxFactory)
{
	$scope.debug = $routeParams.debug;
	$scope.clearError();
	$scope.apiname = $routeParams.apiname;

	$scope.init = function() 
	{
		$scope.url = $routeParams.apiname+"/"+$routeParams.apiid+"/";
		$scope.arguments =  mainConfig["apisObjects"][$routeParams.apiname]["details"];
		$scope.getApiName = ajaxFactory.getApiName;
		ajaxFactory.get($scope.url).then(function(data)
		{
			$scope.item =  data;
		});	
	};

	$scope.del = function() 
	{
		if(confirm("Do you realy want to delete this "+$routeParams.apiname+"?"))
		{
			ajaxFactory.del($scope.url).then(function(data)
			{
				$scope.list();
			});
		}
	};

	$scope.list = function() 
	{
		$location.path("/"+$routeParams.apiname);
	};
	
	$scope.edit = function(item) 
	{
		$location.path("/"+$routeParams.apiname+"/"+$scope.item.id+"/set");
	};
});