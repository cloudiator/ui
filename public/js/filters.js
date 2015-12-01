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

/* Filters */

var paasageFilters = angular.module('paasageFilters', ['ngRoute']);

paasageFilters.filter('optionsFilter', function() 
{
	return function(items,apiName,fieldName,currentForm) 
	{
		var filtered = [];
		if(typeof items !== "undefined")
		{
			var field = mainConfig["apisObjects"][apiName]["form"][fieldName];
			//var dependencyName = field;
			//console.log(field);
			if(typeof field !== "undefined")
			{
				var dependencyName = mainConfig["apisObjects"][apiName]["form"][fieldName]["dependency"];
			}
			if(typeof dependencyName !== "undefined")
			{
				//console.log(dependencyName);
				for (var i = 0; i < items.length; i++)
				{
					var item = items[i];
					var temp = item[dependencyName].id || item[dependencyName];
					if(typeof dependencyName === "undefined" || temp == currentForm[dependencyName]) 
					{
						filtered.push(item);
					}
				}
			}
			else
			{
				filtered = items;
			}
		}
		return filtered;
	};
});

