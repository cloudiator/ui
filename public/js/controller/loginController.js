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

crudControllers.controller('LoginCtrl', function($scope,$rootScope,ajaxFactory,$location)
{
	$scope.clearError();
	ajaxFactory.get("login").then( function(data) { 
		//console.log(data);
		if(data)//TODO change the condition
		{
			$rootScope.user = data;
			//$location.path("/application");
		}
	});
	$scope.login = function() 
	{
		ajaxFactory.post("login", $scope.item).then( function(data) 
		{
			//console.log(data);
			$location.path("/"); 

			$rootScope.user = data;
		},
		function(data) 
		{
			//console.log($scope.item);
			// error returned but may be connected
			//$location.path("/"); 
		});
	};
});