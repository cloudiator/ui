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
crudControllers.controller('ApplicationUpdateCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory,$controller,$q)
{
	$routeParams.apiname = "application";
	angular.extend(this, $controller('CrudUpdateCtrl', {$scope,$rootScope,$location,$routeParams,ajaxFactory}));
	
	$scope.returnForm = function() 
	{		
		$location.path($routeParams.apiname+"/"+$routeParams.apiid);
	};

	$scope.addToCommunicationList = function()
	{
		if($scope.tempCommunication.consumer===undefined
			|| $scope.tempCommunication.provider===undefined
			|| $scope.tempCommunication.port =='' || $scope.tempCommunication.port===null)
		{

			alert("in a Communication, you must have a provider, a consumer and a port number");
		}
		else
		{
			var temp = {
				'requiredPort' : {"name":$scope.tempCommunication.requiredPortName,"applicationComponent":$scope.tempCommunication.consumer[0].id},
				"providedPort" : {"name":$scope.tempCommunication.providedPortPortName,"applicationComponent":$scope.tempCommunication.provider[0].id,"port":$scope.tempCommunication.port},
			};

			$scope.item.communications.push(temp);
			$scope.tempCommunication = {'consumer' : [], 'provider' : [], 'port' : null};
		}
	};

	$scope.refreshVMT = function()
	{
		ajaxFactory.get("virtualMachineTemplate/").then(function(data)
		{
			$scope.vmtList = data;
			angular.forEach($scope.vmtList,function(item)
			{
				item.components = [];
				item.typeOfObject = "vmt";
			});
		});
	}

	$scope.refreshComponents = function()
	{
		ajaxFactory.get("component/").then(function(data)
		{
			$scope.compList = data;
			angular.forEach($scope.compList,function(item)
			{
				item.typeOfObject = "component";
			});
		});
	}

	$scope.init2 = function()
	{
		$scope.appCompList = [];
		$scope.tempComList = [];
		$scope.communicationList = [];
		$scope.tempPort = null;
		$scope.tempCommunication = {'consumer' : [], 'provider' : [], 'port' : null};
		$scope.step = $routeParams.step;
		/*
		if($scope.step==2)
		{
			//$scope.getAppComp();
		}
		else if($scope.step==3)
		{

			//$scope.getCommunication();
		}
		*/
		$scope.refreshVMT();
		$scope.refreshComponents();

		$scope.redirectUrl = $routeParams.apiname+"/"+$routeParams.apiid;
		$scope.url = $routeParams.apiname+"/"+$routeParams.apiid+"/";
		ajaxFactory.get($scope.url).then(function(data)
		{
			$scope.item =  data;
			$scope.base =  angular.copy($scope.item);
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
			//delete $scope.item.applicationComponentsById[1];
			$scope.bob = $scope.item.applicationComponentsById[1];
		});
	}

	$scope.sendForm = function () 
	{
		if($scope.step==2)
		{
			$scope.sendAppComp();
		}
		else if($scope.step==3)
		{
			$scope.sendCommunication();
		}

		var val = {"name" : $scope.item.name };
		if($scope.commands != null)
		{
			val.commands = $scope.commands;
		}
		//alert("what");
		
		ajaxFactory.put($scope.url, val).then(function(data)
		{
			$scope.aaa = data;//$location.path($scope.redirectUrl);
			//$location.path($routeParams.apiname+"/"+$routeParams.apiid);
		});

	}

	$scope.sendAppComp = function () 
	{
		var applicationComponentsOld = [];
		var applicationComponents1 = [];
		var applicationComponents2 = [];

		var commands = [];


		for(key in $scope.item.applicationComponents)
		{
			var value = $scope.item.applicationComponents[key];
			if(value.application)
			{
				applicationComponentsOld.push(value);
			}
			else
			{
				commands.push({
					"method" : "POST",
					"action" : "createOne",
					"apiObject" : "applicationComponent",
					"url" : "/ac",
					"params" : {
						"virtualMachineTemplate" : value.id,
						"component" : value.components[0].id,
						"application" : $scope.item.id
					}
				});
			}
		}

		//get DELETE commands from communicationsOld
		angular.forEach($scope.base.applicationComponents,function(value, key)
		{
			var del = true;
			angular.forEach(applicationComponentsOld,function(value2, key2)
			{
				if(value.id == value2.id)
				{
					del = false;
					if(value.components[0].id != value2.components[0].id)
					{
						value2.application = $routeParams.apiid;

						commands.push({
							"method" : "PUT",
							"action" : "updateOne",
							"apiObject" : "applicationComponent",
							"url" : "/ac/"+value.id,
							"params" : {
								"virtualMachineTemplate" : value2.virtualMachineTemplate,
								"component" : value2.components[0].id,
								"application" : $scope.item.id
								}
						});	
					}
				}
			});

			if(del)
			{
				commands.push({
					"method" : "DELETE",
					"action" : "deleteOne",
					"apiObject" : "applicationComponent",
					"url" : "/ac/"+value.id,
					"params" : {}
				});			
			}
		});
		$scope.commands = commands ;
	}

	$scope.sendCommunication = function () 
	{
		var communicationsOld = [];
		var communicationsNew = [];
		var commands = [];

		//Fill communicationsOld with old communications & add POST command with new communications
		for(key in $scope.item.communications)
		{
			var value = $scope.item.communications[key];
			if(value.id)
			{
				communicationsOld.push(value);
			}
			else
			{
				commands.push({
				"method" : "POST",
				"action" : "createOne",
				"apiObject" : "communication",
				"url" : "communication",
				"params" : value
				});
			}
		}

		//get DELETE commands from communicationsOld
		angular.forEach($scope.base.communications,function(value, key)
		{
			var del = true;
			angular.forEach(communicationsOld,function(value2, key2)
			{
				if(value.id == value2.id)
				{
					del = false;
				}
			});

			if(del)
			{
				commands.push({
					"method" : "DELETE",
					"action" : "deleteOne",
					"apiObject" : "communication",
					"url" : "/communication/"+value.id,
					"params" : {}
				});				
			}
		});
		$scope.commands = commands ;
	}
	$scope.init2();	
});