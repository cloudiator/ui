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

/* App Module */
var paasageApp = angular.module('paasageApp', [
  'ngRoute',
  "dndLists",
  /*'btford.dragon-drop',*/
  'crudControllers',
  'crudFactories',
  'paasageFilters'
]);

paasageApp.config(
	function($routeProvider) 
	{
		$routeProvider
		.when('/login', {
			templateUrl: 'public/partials/login.html',
			controller: 'LoginCtrl'
		})
		.when('/charts', {
			templateUrl: 'public/partials/charts.html',
			controller: 'ChartsCtrl'
		})
		.when('/:apiname', {
			templateUrl: 'public/partials/crud-list.html',
			controller: 'CrudListCtrl'
		})
		.when('/application/add', {
			templateUrl: 'public/partials/application-add.html',
			controller: 'ApplicationAddCtrl'
		})
		.when('/application/:apiid/set/:step', {
			templateUrl: 'public/partials/application-update.html',
			controller: 'ApplicationUpdateCtrl'
		})
		.when('/:apiname/add', {
			templateUrl: 'public/partials/crud-create-update.html',
			controller: 'CrudCreateCtrl'
		})
		.when('/virtualMachine/:apiid', {
			templateUrl: 'public/partials/vm-detail.html',
			controller: 'VMDetailCtrl'
		})
		.when('/monitorInstance/:apiid', {
			templateUrl: 'public/partials/monitorInstance-detail.html',
			controller: 'MonitorInstanceDetailCtrl'
		})
		.when('/rawMonitor/:apiid', {
			templateUrl: 'public/partials/rawMonitor-detail.html',
			controller: 'RawMonitorDetailCtrl'
		})
		.when('/application/:apiid', {
			templateUrl: 'public/partials/application-detail.html',
			controller: 'ApplicationDetailCtrl'
		})
		.when('/:apiname/:apiid', {
			templateUrl: 'public/partials/crud-detail.html',
			controller: 'CrudDetailCtrl'
		})
		.when('/:apiname/:apiid/set', {
			templateUrl: 'public/partials/crud-create-update.html',
			controller: 'CrudUpdateCtrl'
		})
		.otherwise({
			redirectTo: '/application'
		});
	}
);

paasageApp.run(['$route', '$rootScope', '$location', function ($route, $rootScope, $location) {
    $rootScope.newButton = false;
}])
var crudFactories = angular.module('crudFactories', ['ngRoute']);
var crudControllers = angular.module('crudControllers', ['ngRoute']);
