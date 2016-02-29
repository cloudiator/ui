# ExecutionWareUI
-----------------
## Description
The ExecutionwareUI is a PHP and AngularJS Client for the Restful interface of [colosseum](https://github.com/cloudiator/colosseum) and [visor](https://github.com/cloudiator/visor).
It also display graphs by VirtualMachine, these graphs are provided by the KairosDB API.

## Installation

1. Install all dependencies to the project : php5-common, libapache2-mod-php5, php5-cli and apache2
```
sudo apt-get update
sudo apt-get install php5-common libapache2-mod-php5 php5-cli php5-curl apache2
```
2. get the project repository in your web folder (/var/www/html)
```
cd /var/www/html
sudo git clone ssh://gitolite@tuleap.ow2.org/paasage/executionware_ui.git
```
3. change the apache configuration file to allow access to the website :
```
cat << EOF >> /etc/apache2/sites-available/000-default.conf
<Directory /var/www/>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride all
    Order allow,deny
    allow from all
</Directory>
EOF
``` 
4. Start apache, enable the mode rexrite and restart the apache server :
```
sudo service apache2 start
sudo a2enmod rewrite
sudo service apache2 restart
```
You can find a linux script [here](documentation/executionware_ui.sh).
## Configuration
For the configuration of the REST UI, edit the executionware-ui-apis.json file with your url for the APIs.
Do not touch the others parameters.

The executionwareUI the config file on "/etc/paasage/executionware-ui-apis.json". If the file does not exist, then it uses the "executionware-ui-apis.json" from the root of the project.

### executionware-ui-apis.json:
This file allow you to add some APIs to use in the REST UI
#### Example :
```javascript
{
	"colosseumapi" : {
		"name": "colosseumapi",
		"url": "http://localhost:9000/api",
		"controller": "ColosseumController"
	},
	"visorapi" : {
		"name": "visorapi",
		"url": "http://localhost:31415",
		"controller": "VisorController"
	},
	"kairosapi" : {
		"name": "kairosapi",
		"url": "http://localhost:8080/api/v1/datapoints/query",
		"controller": "KairosController"
	}
}
```
Parameter   | Type          | Description
------------|---------------|------------
name        | string        | The name of the API, must be unique
url         | string        | The url of the API
controller  | string        | The controller to use for this API (RestController by default). The controller must be create on the folder app\Controller

## Advanced Configuration
if you use an other version of colosseum, you need to change others configuration files.

[Documentation can be found here](documentation/readme.md)


## License

Copyright 2015 be.wan s.p.r.l.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.