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

       $isis_item = new ISISItem($ItemId);
       return $isis_item;
		
	}
	   
    function GetItems($StartingDate = NULL, $EndingDate = NULL){

        $db = new ISISDb($this->DBName);

        if ($StartingDate !== NULL){
            if ($EndingDate == NULL){
                $EndingDate = date("Y-m-d");
            }
            $date_range_exp = implode(' OR ', range_date($StartingDate, $EndingDate));
        }else{
            $date_range_exp = '';
        }

        $ItemIds = $db->getidentifiers(array('application_path' => APPLICATION_PATH, 'expression' => $date_range_exp));
             
        $ItemIds = explode("|", $ItemIds);
        return $ItemIds;
    }

    # retrieve IDs of items that matches set spec (only needed if sets supported)
    function GetItemsInSet($SetSpec, $StartingDate = NULL, $EndingDate = NULL)
    {
    	
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