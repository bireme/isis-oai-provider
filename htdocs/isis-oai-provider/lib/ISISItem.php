<?PHP

class ISISItem implements OAIItem {

    # ---- PUBLIC INTERFACE --------------------------------------------------

    # object constructor
    function ISISItem($ItemId, $SearchInfo = NULL)
    {
        # save ID for later use
        $this->Id = $ItemId;

        # save any search info supplied
        $this->SearchInfo = $SearchInfo;

        # attempt to create resource object
        $this->Resource = new Resource($ItemId);

        # if resource object creation failed
        if ($this->Resource->Status() == -1)
        {
            # set status to -1 to indicate constructor failure
            $this->LastStatus = -1;
        }
        else
        {
            # set status to 1 to indicate constructor success
            $this->LastStatus = 1;
        }
    }

    function GetId() {  return $this->Id;  }

    function GetDatestamp()
    {
        $DateString = $this->Resource->Get("Date Of Record Creation");
        if ($DateString == "0000-00-00 00:00:00") {  $DateString = date("Y-m-d");  }
        $Date = new Date($DateString);
        return $Date->FormattedISO8601();
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
        $Sets = array();

        # for each possible metadata field
        $Schema = new MetadataSchema();
        $Fields = $Schema->GetFields(MetadataSchema::MDFTYPE_TREE|MetadataSchema::MDFTYPE_CONTROLLEDNAME|MetadataSchema::MDFTYPE_OPTION);
        foreach ($Fields as $Field)
        {
            # if field is flagged for use for OAI sets
            if ($Field->UseForOaiSets())
            {
                # retrieve values for resource for this field and add to set list
                $FieldName = $Field->Name();
                $Values = $this->Resource->Get($FieldName);
                if (isset($Values) && ($Values != NULL))
                {
                    $NormalizedFieldName = $this->NormalizeForSetSpec($FieldName);
                    if (is_array($Values) && count($Values))
                    {
                        foreach ($Values as $Value)
                        {
                            $Sets[] = $NormalizedFieldName.":"
                                    .$this->NormalizeForSetSpec($Value);
                        }
                    }
                    else
                    {
                        $Sets[] = $NormalizedFieldName.":"
                                .$this->NormalizeForSetSpec($Values);
                    }
                }
            }
        }

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
