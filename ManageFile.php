<?php

/********************* INCLUDE **************************/
include "HandleLogger.php";
/********************** END INCLUDE ********************/

class ManageFile
{
    const FILE_CSV  = 'csv';
    const FILE_JSON = 'json';

    const FIELD_TYPE_MAIL = 'mail';
    const FIELD_TYPE_URL = 'url';
    const FIELD_TYPE_BOOLEAN = 'boolean';
    const FIELD_TYPE_FLOAT = 'float';
    const FIELD_TYPE_INT = 'int';
    const FIELD_TYPE_IP = 'address_ip';
    const FIELD_TYPE_MAC = 'address_mac';
    const FIELD_TYPE_STRING = 'string';

    private $sFileName;
    private $sFileType;
    private $bHasHeader;
    private $aHeaderType;// contain the first line of a file if the header is present in the file or the developer can add it
    private $aHeaderData;// contain the first data line and we map with the name of the header
    private $aHeaderByIndex;//column number => column name
    private $aData;

    public function __construct(string $sFileName, string $sFileType = self::FILE_CSV, bool $bHasHeader = false, array $aHeaderType = [])
    {
        HandleLogger::debug(HandleLogger::generateTitle('CONSTRUCT OBJECT MANAGE FILE'));
        HandleLogger::debug('Params (FileName:' . $sFileName . ', FileType:' . $sFileType . ', HasHeader:' . $bHasHeader . ', Header[]: ' . HandleLogger::arrayToString($aHeaderType));

        $this->sFileName  = $sFileName;
        $this->sFileType  = $sFileType;
        $this->bHasHeader = $bHasHeader;
        $this->aHeaderType    = $aHeaderType;
    }

    private function parseFile()
    {
        HandleLogger::debug(HandleLogger::generateTitle('PARSE FILE'));

        $this->aData       = [];
        $this->aHeaderData = [];
        $this->aHeaderType = [];
        $this->aHeaderByIndex = [];
        $row               = 0;

        if (($handle = fopen($this->sFileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                if (true === $this->bHasHeader) {
                    if (1 === $row) {
                        $aNextData = [];
                        foreach ($data as $nKey => $oValue) {
                            $this->aHeaderData[$this->aHeaderByIndex[$nKey]] = $oValue;
                            $aNextData[$this->aHeaderByIndex[$nKey]] = $oValue;
                        }
                        array_push($this->aData, $aNextData);
                    } else if (0 === $row) {
                        foreach ($data as $oValue) {
                            $this->aHeaderType[$oValue] = self::FIELD_TYPE_STRING;
                            array_push($this->aHeaderByIndex,$oValue);
                        }
                    }
                    else {
                        $aNextData = [];
                        foreach ($data as $nKey => $oValue) {
                            $aNextData[$this->aHeaderByIndex[$nKey]] = $oValue;
                        }
                        array_push($this->aData, $aNextData);
                    }

                } else if (false === $this->bHasHeader && 0 === $row) {
                    foreach ($data as $oValue) {
                        $this->aHeaderData[$this->aHeaderByIndex[$nKey]] = $oValue;
                    }
                } else {
                    $aNextData = [];
                    foreach ($data as $nKey => $oValue) {
                        $aNextData[$this->aHeaderByIndex[$nKey]] = $oValue;
                    }
                    array_push($this->aData, $aNextData);
                }
                $row++;
            }
            fclose($handle);
        }

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aData'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aData));
    }

    public function generateHeader()
    {
        HandleLogger::debug(HandleLogger::generateTitle('GENERATE TYPE HEADER'));

        /** Analysis of the first data row */
        /** Detector Type */
        foreach ($this->aHeaderData as $sName => $oValue) {
            
            //if is a mail
            if($oValue === filter_var($oValue, FILTER_VALIDATE_EMAIL)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_MAIL;
            }
            else if(true === filter_var($oValue, FILTER_VALIDATE_MAC)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_MAC;
            }
            //if is an URL
            else if(true === filter_var($oValue, FILTER_VALIDATE_URL)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_URL;
            }
            //if is a boolean
            else if(true === filter_var($oValue, FILTER_VALIDATE_BOOLEAN)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_BOOLEAN;
            }
            //if is a float
            else if(true === filter_var($oValue, FILTER_VALIDATE_FLOAT)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_FLOAT;
            }
            //if is an adress IP
            else if(true === filter_var($oValue, FILTER_VALIDATE_IP)){
                $this->aHeaderType[$sName] = self::FILED_TYPE_IP;
            }
            else if(true === filter_var($oValue, FILTER_VALIDATE_INT)){
                $this->aHeaderType[$sName] = self::FIELD_TYPE_INT;
            }
            else{
                $this->aHeaderType[$sName] = self::FIELD_TYPE_STRING;
            }

            //elif is a firstname
            //elif is a lastname
            
            //elif is a address
            //elif is a date
            //elif is a city
            //elif is a zipcode
            //elif is a time
            //elif is a datetime
            //elif is a token
        }

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeader'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aHeaderType));

        
        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeaderData'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aHeaderData));
    }

    public function anonymise()
    {
        HandleLogger::debug(HandleLogger::generateTitle('ANONYMISE'));

        $this->parseFile();

        if (true === $this->bHasHeader && self::FILE_CSV === $this->sFileType) {
            $this->generateHeader();
            $this->castArrayDataType();
        }
    }

    public function castArrayDataType(){
        
    }

    public function generateFile($sPrefix = 'john_doe_')
    {
        HandleLogger::debug(HandleLogger::generateTitle('GENERATE - EXPORT FILE'));

        $sDestFile = $sPrefix . $this->sFileName;

        HandleLogger::debug('Export File Name : ' . $sDestFile);
    }
}
