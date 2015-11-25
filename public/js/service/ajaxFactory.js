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
 
crudFactories.factory('ajaxFactory', function ($http,$q,$rootScope,$location,resolverFactory)
{
	var factory = 
	{
		lazyGet : function(url) { return this.request("GET",factory.getApiUrl()+"/"+url+"/"+lazy); },
		simplePost : function(url,params) { return this.request("POST",url,params); },
		get : function(url) { return this.request("GET",factory.getApiUrl()+"/"+url); },
		del : function(url) { return this.request("DELETE",factory.getApiUrl()+"/"+url); },
		post : function(url,params) { return this.request("POST",factory.getApiUrl()+"/"+url,params); },
		put : function(url,params) { return this.request("PUT",factory.getApiUrl()+"/"+url,params); },
		request : function(method,url,params)
		{
			url = factory.cleanUrl(method,url);

			$rootScope.loading = true;
			var deferred = $q.defer();
			
			var req = {
				method: method,
				url: url,
				data: { params }
			};
			
			//console.log(url);
			$http(req)
			.success(function(data, status)
			{
				/*console.log(url);
				console.log(data);
				console.log(status);*/
				deferred.resolve(data);
				resolverFactory.resolveSuccess(data, status);
			})
			.error(function(data, status) 
			{	
				deferred.reject(data);
				resolverFactory.resolveError(data, status);
			});
	
			return deferred.promise;
		},
		getApiUrl : function()
		{
			return mainConfig["self"].substring(1)+"/mainapi";
		},
		cleanUrl : function(method,url)
		{
			if(url[0]=="/")
				url = url.substring(1);

			if(url[url.length-1]=="/")
				url = url.substring(0,url.length-1);

			if(method=="POST")
				url = "/"+url;
			else
				url = url+"/";

			return url;
		},
		getOptions : function(subForm) //useless, need to use server side
		{
			var reqopt = [];
			for(var key in subForm)
			{
				if(subForm[key]=='apiObject')
				{
					if ( $.inArray(key, reqopt) === -1 ) 
					{
						reqopt.push(key);
					}
				}
				else if(angular.isObject(subForm[key]))
				{
					
					if(subForm[key]["type"]=='apiObject')
					{
						if(angular.isArray(subForm[key]["name"]))
						{
							for(var key2 in subForm[key]["name"])
							{
								if ( $.inArray(subForm[key]["name"][key2], reqopt) === -1 ) 
								{	
									reqopt.push(subForm[key]["name"][key2]);
								}
							}
						}
						else if ( $.inArray(subForm[key]["name"], reqopt) === -1 ) 
						{	
							reqopt.push(subForm[key]["name"]);
						}
						
					}
					else if(subForm[key]["type"]=='internalObject')
					{
						reqopt = reqopt.concat(factory.getOptions(subForm[key]["content"]));
					}		
					
				}
			}
			return reqopt;
		},		
		getOptions2 : function(subForm) 
		{
			var reqopt = [];
			for(var key in subForm)
			{
				if(subForm[key]=='apiObject')
				{
					if ( $.inArray(key, reqopt) === -1 ) 
					{
						var obj = {"apiObjectName": factory.getApiName(key), "fieldName": key,"isArray" : false};
						reqopt.push(obj);
					}
				}
				else
				{
					if(factory.isArray(subForm[key]))
					{
						if(angular.isObject(subForm[key][0]))
						{
							if(subForm[key][0]["type"]=='apiObject')
							{

								if ( $.inArray(subForm[key][0]["name"], reqopt) === -1 ) 
								{		
									var obj = {"apiObjectName": factory.getApiName(subForm[key][0]["name"]), "fieldName": key,"isArray" : true};
									reqopt.push(obj);
								}
							}				
						}		
					}
					else if(angular.isObject(subForm[key]))
					{
						if(subForm[key]["type"]=='apiObject')
						{
							if ( $.inArray(subForm[key]["name"], reqopt) === -1 ) 
							{	
								var obj = {"apiObjectName":  factory.getApiName(subForm[key]["name"]), "fieldName": key,"isArray" : false};
								reqopt.push(obj);
							}
						}				
					}
				}
			}
			return reqopt;
		},
		
		isArray : function(array)
		{
			return angular.isArray(array);// || (angular.isObject(array) && array.isArray);
		},
		getApiName : function(field)
		{
			return field;//mainConfig["apisObjects"][apiName]["entityName"] ;
		},
		newButtonAction : function(newUrl,currentUrl,item,fieldName) 
		{
			$rootScope.newButton = true;
			if(typeof $rootScope.currentForms === 'undefined')
			{
				$rootScope.currentForms = [];
			}
			$rootScope.currentForms.push({
				url: currentUrl,
				fieldName: fieldName,
				item : item
			});
			$location.path(newUrl);
		}
	};
	return factory;
});