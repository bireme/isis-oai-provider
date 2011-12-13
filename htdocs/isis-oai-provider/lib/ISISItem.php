<?PHP

class ISISItem implements OAIItem {

    # ---- PUBLIC INTERFACE --------------------------------------------------

    # object constructor
    function ISISItem($ItemId, $SearchInfo = NULL)
    {
        # save ID for later use
        $this->Id = $ItemId;
        $id_parts = explode('-', $ItemId);
        $this->DBName = $id_parts[0];

        $this->Resource = new ISISDb($this->DBName);
        

    }

    function GetId() {  
        return $this->Id;  
    }

    function GetDatestamp()
    {
        $DateString = "0000-00-00 00:00:00";
        if ($DateString == "0000-00-00 00:00:00") {  $DateString = date("Y-m-d");  }
        return $DateString;
    }

    function GetMetadata(){

        return $this->Resource->search(array('expression' => $this->Id));
    }

    function GetValue($ElementName)
    {
        # retrieve value
        $ReturnValue = $this->Resource->GetByFieldId($ElementName);

        # strip out any HTML tags if text value
        if (is_string($ReturnValue))
        {
            $ReturnValue = strip_tags($ReturnValue);
        }

        # format correctly if standardized date
        if ($this->GetQualifier($ElementName) == "W3C-DTF")
        {
            $Timestamp = strtotime($ReturnValue);
            $ReturnValue = date('Y-m-d\TH:i:s', $Timestamp)
                    .substr_replace(date('O', $Timestamp), ':', 3, 0);
        }

        # return value to caller
        return $ReturnValue;
    }

    function GetQualifier($ElementName)
    {
        $ReturnValue = NULL;
        $Qualifier = $this->Resource->GetQualifierByFieldId($ElementName, TRUE);
        if (is_array($Qualifier))
        {
            foreach ($Qualifier as $ItemId => $QualObj)
            {
                if (is_object($QualObj))
                {
                    $ReturnValue[$ItemId] = $QualObj->Name();
                }
            }
        }
        else
        {
            if (isset($Qualifier) && is_object($Qualifier))
            {
                $ReturnValue = $Qualifier->Name();
            }
        }
        return $ReturnValue;
    }

    function GetSets()
    {
        # start out with empty list
        $Sets = array($this->DBName);


        # return list of sets to caller
        return $Sets;
    }

    function GetSearchInfo()
    {
        return $this->SearchInfo;
    }

    function Status()
    {
        return $this->LastStatus;
    }


    # ---- PRIVATE INTERFACE -------------------------------------------------

    var $Id;
    var $DBName;
    var $Resource;
    var $LastStatus;
    var $SearchInfo;

    # normalize value for use as an OAI set spec
    function NormalizeForSetSpec($Name)
    {
        return preg_replace("/[^a-zA-Z0-9\-_.!~*'()]/", "", $Name);
    }
}


?>
