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
crudControllers.controller('ApplicationAddCtrl', function($scope,$rootScope,$location,$routeParams,ajaxFactory,$controller)
{
	$routeParams.apiname = "application";
	angular.extend(this, $controller('CrudCreateCtrl', {$scope,$rootScope,$location,$routeParams,ajaxFactory}));

	$scope.setStep = function(i)
	{
		var cont = true;
		$scope.step+=i;
		if($scope.step==3)
		{
			for(key in $scope.appCompList)
			{
				$scope.appCompList[key].positionInACList = key;
				if($scope.appCompList[key].components.length != 1)
				{
					cont = false;
				}
			}
			if(!cont)
			{
				alert("You can not add a virtual machine template without component to your application");
				$scope.step--;
			}
		}
		if($scope.step==2)
		{
			if($scope.item.name =='' || $scope.item.name===undefined)
			{
				alert("The name of the application should not be empty");
				$scope.step--;
			}
		}
	}

	$scope.addToCommunicationList = function()
	{
		var temp = {
			'consumer' : $scope.tempCommunication.consumer[0], 
			'provider' : $scope.tempCommunication.provider[0], 
			'port' : $scope.tempCommunication.port
		};

		if(temp.consumer===undefined
			|| temp.provider===undefined
			|| temp.port =='' || temp.port===null)
		{

			alert("in a Communication, you must have a provider, a consumer and a port number");
		}
		else
		{
			$scope.communicationList.push(temp);
			$scope.tempCommunication = {'consumer' : [], 'provider' : [], 'port' : null};
		}
	}

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

	$scope.sendForm = function () 
	{
		// applicationComponents
		var applicationComponents = [];
		for(key in $scope.appCompList)
		{
			applicationComponents.push({
				"component" : $scope.appCompList[key].components[0].id,
				"virtualMachineTemplate" : $scope.appCompList[key].id
			});
		}

		// communications		
		var communications = [];
		for(key in $scope.communicationList)
		{
			communications.push({
				"provider" :  $scope.communicationList[key].provider.positionInACList,
				"consumer"  : $scope.communicationList[key].consumer.positionInACList,
				"port" : $scope.communicationList[key].port
			});
		}

		var post = { 
			application : $scope.item, 
			applicationComponents : applicationComponents,
			communications : communications 
		};

		ajaxFactory.post($scope.apiname, post).then(function(data)
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
				$location.path($routeParams.apiname+"/"+data.id);
			}
		});
	};

	$scope.init2 = function()
	{
		$scope.appCompList = [];
		$scope.provider = [];
		$scope.consumer = [];
		$scope.tempComList = [];
		$scope.communicationList = [];
		$scope.tempPort = null;
		$scope.tempCommunication = {'consumer' : [], 'provider' : [], 'port' : null};
		$scope.step = 1;
		$scope.refreshVMT();
		$scope.refreshComponents();
		$scope.init();
	};

	$scope.init2();	
});