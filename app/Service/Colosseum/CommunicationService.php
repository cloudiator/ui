<?php
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
class CommunicationService extends ColosseumService
{	

  /***********************************/
 /*									*/
/***********************************/
    public static function createOne($json_url, $putData)
    {
    	$postData = json_decode($putData);	
		$requiredPort  = parent::createOne( "/portReq", 		json_encode($postData->requiredPort) );	
		$providedPort  = parent::createOne( "/portProv", 		json_encode($postData->providedPort) );	
		$communication = parent::createOne( "/communication", 	json_encode([ "requiredPort" => $requiredPort->id, "providedPort" => $providedPort->id]) );	
		return static::parseResult($communication);
    }

    public static function deleteOne($apiname,$val)
    {		
    	$url ="/$apiname/$val";
		$communication = parent::getOne( $url);	
		parent::deleteOne( $url);	
		$requiredPort  = parent::deleteOne( "/portReq/".$communication->requiredPort);	
		$providedPort  = parent::deleteOne( "/portProv/".$communication->providedPort);	
		return static::parseResults($communication);
    }
}