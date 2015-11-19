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
 
crudFactories.factory('resolverFactory', function ($rootScope,$location)
{
	var factory = 
	{
		resolveSuccess : function(data, status)
		{
			$rootScope.loading = false;
			$rootScope.logged = true;
			//TODO link the error click here
			//$rootScope.clearError();
			//$rootScope.debug = data;
			//console.log(data);
		},
		resolveError : function(data, status)
		{
			$rootScope.loading = false;
			$rootScope.logged = false;
			if(status==401)
			{
				$rootScope.logged = false;	
				$rootScope.errors = "you are not logged";
				$location.path("/login"); 
			}
			else if(status==500)
			{
				$rootScope.errors = "error server";// (maybe a foreign key constraint error)";
				//$location.path("/login");	
			}
			else if(status==404)
			{
				//$scope.location.path("login");	
				$rootScope.errors = "page not found";
			}
			else
			{
				$rootScope.errors = "error "+status;
			}
			$rootScope.errors = data;
			//
			//console.log(data);
		}
	};
	return factory;
});