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

crudControllers.controller('AbstractFormCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory)
{
	$scope.debug = $routeParams.debug;
	$scope.clearError();
	$scope.types =  mainConfig["enums"];
	$scope.apiname = $routeParams.apiname;

	$scope.newButtonAction = function(url,fieldName)
	{
		ajaxFactory.newButtonAction(url,$routeParams.apiname+"/add",$scope.item,fieldName);
	}

	$scope.returnForm = function() 
	{		
		if(typeof $rootScope.currentForms !== 'undefined' && $rootScope.currentForms.length>0)
		{
			var parentForm = $rootScope.currentForms.pop();
			//parentForm.item[parentForm.fieldName] = data.id;
			$rootScope.currentForm = parentForm;
			$location.path(parentForm.url);				
		}
		else
		{
			$location.path($routeParams.apiname);
		}
	};
});