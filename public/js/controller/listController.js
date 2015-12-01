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

crudControllers.controller('CrudListCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory)
{
	$scope.setPage = function (pageNo) {
		$scope.currentPage = pageNo;
	};

	$scope.pageChanged = function() {
		console.log('Page changed to: ' + $scope.currentPage);
	};

	$scope.setItemsPerPage = function(num) {
		$scope.itemsPerPage = num;
		$scope.currentPage = 1; //reset to first page
	}

	$scope.clearError();
	$scope.apiname = $routeParams.apiname;
	$scope.url = $scope.apiname+"/";
	$scope.arguments = $rootScope.apisObjects[$scope.apiname]["list"];

	ajaxFactory.get($scope.url).then(function(data)
	{
		$scope.data = data;

		$scope.viewby = 10;
		$scope.totalItems = $scope.data.length;
		$scope.currentPage = 1;
		$scope.itemsPerPage = $scope.viewby;
		$scope.maxSize = 10; //Number of pager buttons to show
	});	

	$scope.del = function(item) 
	{
		$scope.clearError();
		if(confirm("Do you realy want to delete this "+$scope.apiname+"?"))
		{
			ajaxFactory.del($scope.url+item.id+"/").then(function(data)
			{
				$scope.data.splice($scope.data.indexOf(item), 1);
			});			
		}
	};

	$scope.see = function(item) 
	{
		$location.path($scope.url+item.id);
	};

	$scope.edit = function(item) 
	{
		$location.path($scope.url+item.id+'/set');
	};

	$scope.add = function() 
	{
		$location.path($scope.url+'add');
	};
});