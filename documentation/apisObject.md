
# config/apisObject.json :
## Description
This file allow you to describe the entities of the APIs.

## Example :
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
           "age" : 
        	{ 
        		"type" : "int"
        	},
        	"timeUnit" : 
        	{ 
        		"type" : "enum", 
        		"name" : "timeUnit"
        	}
		}
	},
	"objectName2" :  
	{ 
		//...
	}
}
```
Parameter   | Type          | Description
------------|---------------|------------
api         | int           | The index of the binded API from the apis.json file.
entityName  | string        | The name of the entity in the current API, the default value is the json object name ("objectName1" in the first exemple)
crud        | array[string] | A list of crud action (default : ["list","get","post","put","del"]).
pk          | string        | The privateKey field of the entity (Default : "id")
form        | array[field]  | The list of fields from the entity, you can see a descruipiton of the fields type in the next section


## Field Types :

### 1. String, Int, Number and boolean

#### Example

```javascript
...
		"form" : 
		{ 
            "age" : "int",
            "firstname" :"string",
            "lastname" : {  "type" : "string" },
            "isMaried" : "boolean"
		}
...
```
> As you can see, you can use the short version (age,firstname) or the long version (lastname) to define one of these type without any differences.

### 2. Enum and apiObject

#### Example

```javascript
...
		"form" : 
		{ 
            "month" : "enum",
            "car" : 
            {  
                "type" : "apiObject",
                "name" : "cars"
            }
		}
...
```
> You can use the short version to define an enumeration/apiObject if the name of the enumeration/apiObject is the same as the name of the field. Otherwise, you must use the long version and define the filed "name" with the enumeration/apiObject name.

### 3. internalObject

#### Example

```javascript
...
		"form" : 
		{ 
            "Internal 1" : 
			{
				"type" : "internalObject",
				"content" : 
				{
					"number" : "int",
					"id" : "int"
				}
			}
		}
...
```

> You can use an internal object using the type "internalObject". the "internalObject" must be describe in the content parameter.

## Other options
### 1. isArray

#### Example

```javascript
...
		"form" : 
		{ 
            "cars" : 
            {  
                "type" : "apiObject",
                "name" : "cars",
                "isArray" : true
            }
		}
...
```

> You can initialize the "isArray" attribute to true to use an array of data for the current field


### 2. dependency

The dependency field allow you to describe a internal field that is a dependency. In this case, image depende on cloud, it mean only image that image.cloud == cloud will be displayed.

#### Example

```javascript
...
	"form" : 
	{
		"name" : "string", 
		"cloud" : "apiObject", 	
		"image" : 
		{ 
			"type" : "apiObject", 
			"name" : "image",
			"dependency" : "cloud"
		}
	}
...
```