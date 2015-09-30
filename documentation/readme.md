## Advanced Configuration

### config/navs.json :
This file define the navbar of the UI. It is an array of navItem.
#### Example :
```javascript
[
	{"apiObject" : "apiObjectName", "name" : "navName"},
	{
		"name" : "application", 
		"group" : 
		[
			{"apiObject" : "application"},
			{"apiObject" : "instance"}
		]
	}
]
```
Parameter   | Type          | Description
------------|---------------|------------
name        | string        | The name to display in the nav (default : apiObject value)
apiObject   | string        | The API object name from the apisObject
group       | array[navItem]| It is a subnav

### config/enums.json :
This file allow you to describe a list of custom enumeration that you can use in all apiobjects.
#### Example :
```javascript
{
	"enum1" : ["val1","val2"],
	"enum2" : ["val3","val4"]
}
```
### config/apisObject.json :
#### Description
This file allow you to describe the entities of the APIs.

#### Example :
```javascript
{
	"objectName1" : 
	{
		"api" : 0, 
		"entityName" : "ip",
		"crud" : ["list","get","post","put","del"],
		"pk" : "id",
		"form" : 
		{ 
           ...
		}
	},
	"objectName2" :  
	{ 
		...
	}
}
```
Parameter   | Type          | Description
------------|---------------|------------
api         | int           | The index of the binded API from the apis.json file.
entityName  | string        | The name of the entity in the current API, the default value is the json object name ("objectName1" in the first exemple)
crud        | array[string] | A list of crud action (default : ["list","get","post","put","del"]).
pk          | string        | The privateKey field of the entity (Default : "id")
form        | array[field]  | The list of fields from the entity

> To know how to define fileds, [use this documentation](apisObject.md) .
