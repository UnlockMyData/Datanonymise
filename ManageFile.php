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
    const filter_var_TYPE_IP = 'address_ip';
    const filter_var_TYPE_MAC = 'address_mac';
    const filter_var_TYPE_STRING = 'string';

    private $sFileName;
    private $sFileType;
    private $bHasHeader;
    private $aHeader;
    private $aHeaderData;
    private $aData;

    public function __construct(string $sFileName, string $sFileType = self::FILE_CSV, bool $bHasHeader = false, array $aHeader = [])
    {
        HandleLogger::debug(HandleLogger::generateTitle('CONSTRUCT OBJECT MANAGE FILE'));
        HandleLogger::debug('Params (FileName:' . $sFileName . ', FileType:' . $sFileType . ', HasHeader:' . $bHasHeader . ', Header[]: ' . HandleLogger::arrayToString($aHeader));

        $this->sFileName  = $sFileName;
        $this->sFileType  = $sFileType;
        $this->bHasHeader = $bHasHeader;
        $this->aHeader    = $aHeader;
    }

    private function parseFile()
    {
        HandleLogger::debug(HandleLogger::generateTitle('PARSE FILE'));

        $this->aData       = [];
        $this->aHeaderData = [];
        $this->aHeader     = [];
        $row               = 0;

        if (($handle = fopen($this->sFileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                if (true === $this->bHasHeader) {
                    if (1 === $row) {
                        foreach ($data as $nKey => $oValue) {
                            $this->aHeaderData[$this->aHeader[$nKey]] = $oValue;
                        }
                    } else if (0 === $row) {
                        foreach ($data as $oValue) {
                            array_push($this->aHeader, $oValue);
                        }
                    }

                } else if (false === $this->bHasHeader && 0 === $row) {
                    foreach ($data as $oValue) {
                        $this->aHeaderData[$this->aHeader[$nKey]] = $oValue;
                    }
                } else {
                    $aNextData = [];
                    foreach ($data as $nKey => $oValue) {
                        $aNextData[$this->aHeader[$nKey]] = $oValue;
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

            $nIndexHeaderAssign = array_search($sName, $this->aHeader);

            //if is a mail
            if(true === filter_var($oValue, filter_var_VALIDATE_EMAIL)){
                $this->aHeader[$nIndexHeaderAssign] = self::FIELD_TYPE_MAIL;
            }
            else if(true === filter_var($oValue, filter_var_VALIDATE_MAC)){
                $this->aHeader[$nIndexHeaderAssign] = self::filter_var_TYPE_MAC;
            }
            //if is an URL
            else if(true === filter_var($oValue, filter_var_VALIDATE_URL)){
                $this->aHeader[$nIndexHeaderAssign] = self::FIELD_TYPE_URL;
            }
            //if is a boolean
            else if(true === filter_var($oValue, filter_var_VALIDATE_BOOLEAN)){
                $this->aHeader[$nIndexHeaderAssign] = self::FIELD_TYPE_BOOLEAN;
            }
            //if is a float
            else if(true === filter_var($oValue, filter_var_VALIDATE_FLOAT)){
                $this->aHeader[$nIndexHeaderAssign] = self::FIELD_TYPE_FLOAT;
            }
            //if is an adress IP
            else if(true === filter_var($oValue, filter_var_VALIDATE_IP)){
                $this->aHeader[$nIndexHeaderAssign] = self::FILED_TYPE_IP;
            }
            else if(true === filter_var($oValue, filter_var_VALIDATE_INT)){
                $this->aHeader[$nIndexHeaderAssign] = self::FIELD_TYPE_INT;
            }
            else{
                $this->aHeader[$nIndexHeaderAssign] = self::filter_var_TYPE_STRING;
            }

            //elif is a firstname
            //elif is a lastname
            
            //elif is a address
            //elif is a date
            //elif is a city
            //elif is a zipcode
            //elif is a time
            //elif is a datetime
        }

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeader'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aHeader));
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

    public function generateFile($sPrefix = 'john_doe_')
    {
        HandleLogger::debug(HandleLogger::generateTitle('GENERATE - EXPORT FILE'));

        $sDestFile = $sPrefix . $this->sFileName;

        HandleLogger::debug('Export File Name : ' . $sDestFile);
    }
}
