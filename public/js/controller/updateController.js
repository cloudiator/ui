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
//'use strict';
/* Controllers */
crudControllers.controller('CrudUpdateCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory,$controller)
{
	$scope.clearError();
	$scope.crudTitle = "Update";
	$scope.apiid = $routeParams.apiid;
	$scope.init = function()
	{
		$rootScope.newButton = false;
		$rootScope.currentForms = [];

		$scope.redirectUrl = $routeParams.apiname+"/"+$routeParams.apiid;
		$scope.url = $routeParams.apiname+"/"+$routeParams.apiid+"/";
		ajaxFactory.get($scope.url).then(function(data)
		{
			$scope.item =  data;
			
			$scope.apisObject =  mainConfig["apisObjects"][$routeParams.apiname];
			$scope.form =  $scope.apisObject["form"];
			$scope.reqopt = ajaxFactory.getOptions($scope.form);
					
			if($scope.reqopt.length>0)
			{
				var url = "options";
				if($scope.reqopt.length>0)
				{				
					ajaxFactory.post(url, $scope.reqopt)
					.then(function(data)
					{
						//api.tostring
						$scope.options = data;
					});				
				}
			}
			
			if(typeof $rootScope.currentForm !== 'undefined')
			{
				$scope.item = $rootScope.currentForm.item;
				delete $rootScope.currentForm;
			}
		});
	}
	
	$scope.sendForm = function() 
	{
		ajaxFactory.put($scope.url, $scope.item).then(function(data)
		{
			$location.path($scope.redirectUrl);
		});
	};	

	angular.extend(this, $controller('AbstractFormCtrl', {$scope,$rootScope,$location,$routeParams,ajaxFactory}));
	$scope.init();
});