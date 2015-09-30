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

crudControllers.controller('CrudCreateCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory,$controller)
{
	$scope.crudTitle = "Create";
	//$scope.apiname = $routeParams.apiname;
	$scope.init = function()
	{
		if($rootScope.newButton || typeof $rootScope.currentForm !== 'undefined')
		{
			$rootScope.newButton = false;
		}
		else
		{
			$rootScope.currentForms = [];
		}

		// initialize item
		$scope.item = {};
		if(typeof $rootScope.currentForm !== 'undefined')
		{
			$scope.item = $rootScope.currentForm.item;
			delete $rootScope.currentForm;
		}
		
		$scope.forms = $rootScope.apisObjects;
		$scope.form =  $scope.forms[$scope.apiname]["form"];


		$scope.reqopt = ajaxFactory.getOptions($scope.form);
		if($scope.reqopt.length>0)
		{
			var url = "options";
			ajaxFactory.post(url, $scope.reqopt).then(function(data)
			{
				$scope.options = data;
			},function(data)
			{
				$scope.options = data;
			});
		}
	}
	
	
	$scope.sendForm = function() 
	{		
		ajaxFactory.post($scope.apiname, $scope.item).then(function(data)
		{
			if(typeof $rootScope.currentForms !== 'undefined' && $rootScope.currentForms.length>0)
			{
				var parentForm = $rootScope.currentForms.pop();
				parentForm.item[parentForm.fieldName] = data.id;
				$rootScope.currentForm = parentForm;
				$location.path(parentForm.url);				
			}
			else
			{
				$location.path($scope.apiname+"/"+data.id);
			}
		});
	};
	
	angular.extend(this, $controller('AbstractFormCtrl', {$scope,$rootScope,$location,$routeParams,ajaxFactory}));
	$scope.init();
});