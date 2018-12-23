<?php

/********************* INCLUDE **************************/
include "HandleLogger.php";
/********************** END INCLUDE ********************/

class ManageFile
{
    const FILE_CSV  = 'csv';
    const FILE_JSON = 'json';

    const FIELD_TYPE_MAIL    = 'mail';
    const FIELD_TYPE_URL     = 'url';
    const FIELD_TYPE_BOOLEAN = 'boolean';
    const FIELD_TYPE_FLOAT   = 'float';
    const FIELD_TYPE_INT     = 'int';
    const FIELD_TYPE_IP      = 'address_ip';
    const FIELD_TYPE_MAC     = 'address_mac';
    const FIELD_TYPE_STRING  = 'string';
    const FIELD_TYPE_DATE    = 'date';
    const FIELD_TYPE_DATETIME= 'datetime';
    const FIELD_TYPE_TIME    = 'time';
    const FIELD_TYPE_TIMEZONE    = 'timezone';

    const FIELD_TYPE_FIRSTNAME = 'firstname';
    const FIELD_TYPE_LASTNAME  = 'lastname';
    const FIELD_TYPE_ADDRESS   = 'address';
    const FIELD_TYPE_ZIPCODE   = 'zipcode';
    const FIELD_TYPE_CITY      = 'city';
    const FIELD_TYPE_TOKEN     = 'token';
    const FIELD_TYPE_LATITUDE       = 'latitude';
    const FIELD_TYPE_LONGITUDE      = 'longitude';
    const FIELD_TYPE_ID        = 'id';
    const FIELD_TYPE_COUNTRY_CODE   = 'country_code';
    const FIELD_TYPE_LOCAL_LANGUAGE = 'local_language';

    const DICT_COLUMN_NAME_FIRSTNAME = ['firstname','name'];
    const DICT_COLUMN_NAME_LASTNAME  = ['lastname','surname'];
    const DICT_COLUMN_NAME_ADDRESS   = ['address'];
    const DICT_COLUMN_NAME_ZIPCODE   = ['zipcode'];
    const DICT_COLUMN_NAME_CITY      = ['city'];
    const DICT_COLUMN_NAME_LATITUDE  = ['latitude'];
    const DICT_COLUMN_NAME_LONGITUDE = ['longitude'];
    const DICT_COLUMN_NAME_COUNTRY_CODE = ['country'];
    const DICT_COLUMN_NAME_LOCAL_LANGUAGE = ['locale'];
    const DICT_COLUMN_NAME_TIMEZONE = ['timezone', 'time_zone'];

    private $sFileName;
    private $sFileType;
    private $bHasHeader;
    private $aHeaderType; // contain the first line of a file if the header is present in the file or the developer can add it
    private $aHeaderData; // contain the first data line and we map with the name of the header
    private $aHeaderByIndex; //column number => column name
    private $aData;

    public function __construct(string $sFileName, string $sFileType = self::FILE_CSV, bool $bHasHeader = false, array $aHeaderType = [])
    {
        HandleLogger::debug(HandleLogger::generateTitle('CONSTRUCT OBJECT MANAGE FILE'));
        HandleLogger::debug('Params (FileName:' . $sFileName . ', FileType:' . $sFileType . ', HasHeader:' . $bHasHeader . ', Header[]: ' . HandleLogger::arrayToString($aHeaderType));

        if(false === $bHasHeader && 0 === count($aHeaderType)){
            throw new Exception("it's impossible to have to header inside the file and no header given by the developper");
        }

        $this->sFileName   = $sFileName;
        $this->sFileType   = $sFileType;
        $this->bHasHeader  = $bHasHeader;
        $this->aHeaderType = $aHeaderType;
    }

    private function parseFile()
    {
        HandleLogger::debug(HandleLogger::generateTitle('PARSE FILE'));

        $this->aData          = [];
        $this->aHeaderData    = [];
        $this->aHeaderType    = [];
        $this->aHeaderByIndex = [];
        $row                  = 0;

        if (($handle = fopen($this->sFileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if (true === $this->bHasHeader) {
                    if (1 === $row) {
                        $aNextData = [];
                        foreach ($data as $nKey => $oValue) {
                            $this->aHeaderData[$this->aHeaderByIndex[$nKey]] = $oValue;
                            $aNextData[$this->aHeaderByIndex[$nKey]]         = $oValue;
                        }
                        array_push($this->aData, $aNextData);
                    } else if (0 === $row) {
                        foreach ($data as $oValue) {
                            $this->aHeaderType[$oValue] = self::FIELD_TYPE_STRING;
                            array_push($this->aHeaderByIndex, $oValue);
                        }
                    } else {
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
            if ($oValue === filter_var($oValue, FILTER_VALIDATE_EMAIL)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_MAIL;
            } else if (true === filter_var($oValue, FILTER_VALIDATE_MAC)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_MAC;
            }
            //if is an URL
            else if (true === filter_var($oValue, FILTER_VALIDATE_URL)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_URL;
            }
            //if is a boolean
            else if (true === filter_var($oValue, FILTER_VALIDATE_BOOLEAN)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_BOOLEAN;
            }
            //if is a float
            else if (true === filter_var($oValue, FILTER_VALIDATE_FLOAT)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_FLOAT;
            }
            //if is an adress IP
            else if (true === filter_var($oValue, FILTER_VALIDATE_IP)) {
                $this->aHeaderType[$sName] = self::FILED_TYPE_IP;
            } else if (true === filter_var($oValue, FILTER_VALIDATE_INT) || ctype_digit($oValue)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_INT;
            } else {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_STRING;

                //analysis with name of the column
                if(in_array($sName, self::DICT_COLUMN_NAME_FIRSTNAME)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_FIRSTNAME;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_LASTNAME)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_LASTNAME;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_ADDRESS)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_ADDRESS;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_CITY)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_CITY;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_COUNTRY_CODE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_COUNTRY_CODE;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_LATITUDE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_LATITUDE;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_LONGITUDE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_LONGITUDE;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_LOCAL_LANGUAGE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_LOCAL_LANGUAGE;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_TIMEZONE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_TIMEZONE;
                }
                else if(in_array($sName, self::DICT_COLUMN_NAME_ZIPCODE)){
                    $this->aHeaderType[$sName] = self::FIELD_TYPE_ZIPCODE;
                }
            }

            //elif is a date
            //elif is a time
            //elif is a datetime
            //elif is a token
        }

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeaderType'));
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
        }
        $this->castArrayDataType();
        $this->replaceRGPData();
        $this->alterAllData();
    }

    public function replaceRGPData(){
        HandleLogger::debug(HandleLogger::generateTitle('REPLACE MAIN DATA RGPD'));
    }

    public function alterAllData(){
        HandleLogger::debug(HandleLogger::generateTitle('ALTER ALL DATA'));
    }

    public function castArrayDataType()
    {
        HandleLogger::debug(HandleLogger::generateTitle('CAST DATA WITH HEADER INFO'));

        foreach($this->aData as &$oRow){
            foreach($oRow as $sName => &$oValue){
                if(self::FIELD_TYPE_BOOLEAN === $this->aHeaderType[$sName]){
                    $oValue = (boolean) $oValue;
                }
                //if is a float
                else if (true === in_array($this->aHeaderType[$sName],array_merge([self::FIELD_TYPE_FLOAT], self::DICT_COLUMN_NAME_LONGITUDE, self::DICT_COLUMN_NAME_LATITUDE))) {
                    $oValue = (float) $oValue;
                }
                else if (self::FIELD_TYPE_INT === $this->aHeaderType[$sName]) {
                    $oValue = (int) $oValue;
                }
            }
        }
        HandleLogger::debug(HandleLogger::generateTitle('VAR_DUMP aData'));
        HandleLogger::debug(HandleLogger::displayType($this->aData));
    }

    public function generateFile($sPrefix = 'john_doe_')
    {
        HandleLogger::debug(HandleLogger::generateTitle('GENERATE - EXPORT FILE'));

        $sDestFile = $sPrefix . $this->sFileName;

        HandleLogger::debug('Export File Name : ' . $sDestFile);
    }
}
