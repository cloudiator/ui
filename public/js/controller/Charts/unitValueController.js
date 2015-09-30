
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
crudControllers.controller('unitValueController', function($scope)
{	
	$scope.template = 'public/partials/value-unit.html';
	
	$scope.unitNames = ["seconds","minutes","hours","days","months","years"];
	$scope.unitChange = function(clickEvent) 
	{
		$scope.valUnit.unit = clickEvent.currentTarget.innerHTML;
		$scope.change();		
	};
	
	$scope.addValue= function(x) 
	{
		$scope.valUnit.value = +$scope.valUnit.value+x;
		$scope.change();		
	}
	
	$scope.init = function(title,valUnit) 
	{
		$scope.valUnitTitle = title;
		$scope.valUnit = valUnit;
	}
});