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
crudControllers.controller('mainCtrl', function($scope, $rootScope, $route, $routeParams, $location, $http) 
{
	$rootScope.errors = null;
	$rootScope.apisObjects =  mainConfig["apisObjects"];
	$rootScope.logged = false;
	$scope.route = $route;

	/*
	$scope.getApiUrl = function()
	{
		return mainConfig["self"].substring(1)+"/mainapi";
	}
	//*/	
	$scope.clearError = function() 
	{
		$rootScope.errors = null;
	}
	$scope.isArray = function(array)
	{
		return angular.isArray(array);// || (angular.isObject(array) && array.isArray);
	}	
	$scope.getArray = function(array)
	{
		ret = {isArray : true};
		if(angular.isObject(array[0]))
		{
			ret = array[0];
			//alert("");
		}
		else
		{
			ret.type = array;
		}
		ret.ui_isArray = true;
		return ret;
	}	
	$scope.isObject = function(object)
	{
		return ( !angular.isArray(object) && angular.isObject(object) && 
			(  object['type']==undefined || angular.isObject(object['type'])) && 
			(object.ui_isArray!=true || object.type==undefined) );
	}
	$scope.copy = function(object)
	{
		return angular.copy(object);
	}
	$scope.getFields = function(object)
	{
		rets = angular.copy(object);
		delete rets.ui_isArray;
		
		for(key in rets)
		{
			rets[key] = "";
		}
		//*/
		return rets;
	}	
	$scope.apiObjectHave= function(data)
	{
		
		if( $scope.apisObjects[$routeParams.apiname])
		{
			var apiObject =  $scope.apisObjects[$routeParams.apiname];
			if(apiObject.hasOwnProperty('crud'))
			{
				return ($.inArray(data,apiObject.crud)>=0)?true:false;
			}
			else
			{
				return true;
			}
		}
		return false;
	}
});