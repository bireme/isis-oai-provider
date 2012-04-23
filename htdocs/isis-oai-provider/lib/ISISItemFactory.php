<?php

class ISISItemFactory implements OAIItemFactory {

    # ---- PRIVATE VARIABLES -----------------------------------------

    private $SetSpecs;
    private $SetFields;
    private $SetValues;
    private $RetrievalSearchParameters;
    private $SearchScores;
    private $SearchScoreScale;
    private $Databases;

    # object constructor
    function ISISItemFactory($Databases, $RetrievalSearchParameters = NULL)
    {
        # save any supplied retrieval parameters
        $this->RetrievalSearchParameters = $RetrievalSearchParameters;

        $this->Databases = $Databases;
    }


    function GetItem($ItemId) 
	{

        $ItemId = explode("^",$ItemId);
        
        $isis_item = new ISISItem($ItemId[0], $datestamp = $ItemId[1]);
        return $isis_item;
		
	}
	   
    function GetItems($StartingDate = NULL, $EndingDate = NULL, $ListStartPoint=0){

        $db = new ISISDb($this->DBName);
        $ItemIds = '';

        if ($StartingDate !== NULL){
            if ($EndingDate == NULL){
                $EndingDate = date("Y-m-d");
            }
            $date_range_exp = implode(' OR ', range_date($StartingDate, $EndingDate));
        }else{
            $date_range_exp = '';
        }
        $ListStartPoint += 1;
        
        foreach ($this->Databases as $database) { 
            $params = array('expression' => $date_range_exp, 
                'dbpath' => $database['path'],
                'dbname' => $database['setSpec'],
                'date_prefix' => $database['prefix'],
                'id_field' => $database['identifier_field'],
                'date_field' => $database['datestamp_field'],
                'from' => $ListStartPoint,
            );
            $ItemIds .= $db->getidentifiers($params, $database['isis_key_length']);

            $curItemIds = array_filter(explode("|", $ItemIds));
            $total = $db->gettotal($params, $database['isis_key_length']);
            $totalFound = count($curItemIds);

            if($total < $ListStartPoint) {
                $ListStartPoint = $ListStartPoint - $total;
            }
        }       

        $ItemIds = array_filter(explode("|", $ItemIds));
        $ItemIds = array_slice($ItemIds, 0, $ItemsPerPage);
             
        return $ItemIds;
    }

    # retrieve IDs of items that matches set spec (only needed if sets supported)
    function GetItemsInSet($SetSpec, $StartingDate = NULL, $EndingDate = NULL, $ListStartPoint=0)
    {
    	$db = new ISISDb($this->DBName);

        if ($StartingDate !== NULL){
            if ($EndingDate == NULL){
                $EndingDate = date("Y-m-d");
            }
            $date_range_exp = implode(' OR ', range_date($StartingDate, $EndingDate));
        }else{
            $date_range_exp = '';
        }
        $ListStartPoint += 1;
        foreach ($this->Databases as $database) {
            if ($database['setSpec'] == $SetSpec){
                $params = array('expression' => $date_range_exp, 
                  'dbpath' => $database['path'],
                  'dbname' => $database['setSpec'],
                  'date_prefix' => $database['prefix'],
                  'id_field' => $database['identifier_field'],
                  'date_field' => $database['datestamp_field'],
                  'from' => $ListStartPoint,
                );
                $ItemIds = $db->getidentifiers($params, $database['isis_key_length']);
            }     
        } 
        $ItemIds = substr($ItemIds, 0, strlen($ItemIds)-1);
        $ItemIds = explode("|", $ItemIds);
        return $ItemIds;
    }

    # return array containing all set specs (with human-readable set names as keys)
    # (only used if sets supported)
    function GetListOfSets()
    {
    	$setList = array();
    	foreach ($this->Databases as $set){
    		$setSpec = $set['setSpec'];
    		$setName = $set['setName'];

    		$setList[$setName] = $setSpec;
    	}

    	return $setList;

    }

    # retrieve IDs of items that match search parameters (only used if OAI-SQ supported)
    function SearchForItems($SearchParams, $StartingDate = NULL, $EndingDate = NULL)
    {
    	
    }


}

?>