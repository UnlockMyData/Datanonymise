<?php

/********************* INCLUDE **************************/
include "HandleLogger.php";
/********************** END INCLUDE ********************/

class ManageFile
{
    const FILE_CSV  = 'csv';
    const FILE_JSON = 'json';

    const FIELD_TYPE_MAIL     = 'mail';
    const FIELD_TYPE_URL      = 'url';
    const FIELD_TYPE_BOOLEAN  = 'boolean';
    const FIELD_TYPE_FLOAT    = 'float';
    const FIELD_TYPE_INT      = 'int';
    const FIELD_TYPE_IP       = 'address_ip';
    const FIELD_TYPE_MAC      = 'address_mac';
    const FIELD_TYPE_STRING   = 'string';
    const FIELD_TYPE_DATE     = 'date';
    const FIELD_TYPE_DATETIME = 'datetime';
    const FIELD_TYPE_TIME     = 'time';
    const FIELD_TYPE_TIMEZONE = 'timezone';

    const FIELD_TYPE_FIRSTNAME        = 'firstname';
    const FIELD_TYPE_LASTNAME         = 'lastname';
    const FIELD_TYPE_ADDRESS          = 'address';
    const FIELD_TYPE_SECOND_ADDRESS   = 'second_address';
    const FIELD_TYPE_ADDRESS_COMPLETE = 'address_complete';
    const FIELD_TYPE_ZIPCODE          = 'zipcode';
    const FIELD_TYPE_CITY             = 'city';
    const FIELD_TYPE_TOKEN            = 'token';
    const FIELD_TYPE_LATITUDE         = 'latitude';
    const FIELD_TYPE_LONGITUDE        = 'longitude';
    const FIELD_TYPE_ID               = 'id';
    const FIELD_TYPE_COUNTRY_CODE     = 'country_code';
    const FIELD_TYPE_COUNTRY          = 'country';
    const FIELD_TYPE_LOCAL_LANGUAGE   = 'local_language';
    const FIELD_TYPE_STATE            = 'state';
    const FIELD_TYPE_STATE_FULL       = 'state_full';
    const FIELD_TYPE_PHONE_NUMBER     = 'phone_number';
    const FIELD_TYPE_MOBILE_NUMBER    = 'mobile_number';

    const DICT_COLUMN_NAME_FIRSTNAME        = ['firstname', 'name', 'Prénom (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_LASTNAME         = ['lastname', 'surname', 'Nom (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_ADDRESS          = ['address', '﻿Adresse 1 (appartement) (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_SECOND_ADDRESS   = ['Adresse 2 (immeuble) (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_ADDRESS_COMPLETE = ['Adresse saisie (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_ZIPCODE          = ['zipcode', 'Code Postal (Dimension de ciblage)', 'Commune (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_STATE            = ['Département (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_STATE_FULL       = ['Région (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_CITY             = ['city', 'Ville (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_LATITUDE         = ['latitude'];
    const DICT_COLUMN_NAME_LONGITUDE        = ['longitude'];
    const DICT_COLUMN_NAME_COUNTRY_CODE     = ['country', 'Nationalité (Dimension de ciblage)', 'Langue (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_COUNTRY          = ['Pays (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_LOCAL_LANGUAGE   = ['locale'];
    const DICT_COLUMN_NAME_TIMEZONE         = ['timezone', 'time_zone'];
    const DICT_COLUMN_NAME_MOBILE_NUMBER    = ['Téléphone mobile (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_ID               = ['IMEI', 'ID_CONSENTEMENT_EMAIL (Dimension de ciblage)', 'Id \'Contact\' (Dimension de ciblage)','ID \'Code Retour QAS\' (Dimension de ciblage)','Master Id (Dimension de ciblage)','Identifiant Neolane (Dimension de ciblage)','Household Id (Dimension de ciblage)', 'ID \'Civilité\' (Dimension de ciblage)'];
    const DICT_COLUMN_NAME_TOKEN            = ['payment response id', 'shop id', 'item use id', 'product id', 'order id'];

    const ARRAY_DICT_COLUMN_DETECTOR = [
        self::FIELD_TYPE_FIRSTNAME        => self::DICT_COLUMN_NAME_FIRSTNAME,
        self::FIELD_TYPE_LASTNAME         => self::DICT_COLUMN_NAME_LASTNAME,
        self::FIELD_TYPE_ADDRESS          => self::DICT_COLUMN_NAME_ADDRESS,
        self::FIELD_TYPE_SECOND_ADDRESS   => self::DICT_COLUMN_NAME_SECOND_ADDRESS,
        self::FIELD_TYPE_ADDRESS_COMPLETE => self::DICT_COLUMN_NAME_ADDRESS_COMPLETE,
        self::FIELD_TYPE_ZIPCODE          => self::DICT_COLUMN_NAME_ZIPCODE,
        self::FIELD_TYPE_STATE            => self::DICT_COLUMN_NAME_STATE,
        self::FIELD_TYPE_STATE_FULL       => self::DICT_COLUMN_NAME_STATE_FULL,
        self::FIELD_TYPE_CITY             => self::DICT_COLUMN_NAME_CITY,
        self::FIELD_TYPE_LATITUDE         => self::DICT_COLUMN_NAME_LATITUDE,
        self::FIELD_TYPE_LONGITUDE        => self::DICT_COLUMN_NAME_LONGITUDE,
        self::FIELD_TYPE_COUNTRY_CODE     => self::DICT_COLUMN_NAME_COUNTRY_CODE,
        self::FIELD_TYPE_LOCAL_LANGUAGE   => self::DICT_COLUMN_NAME_LOCAL_LANGUAGE,
        self::FIELD_TYPE_TIMEZONE         => self::DICT_COLUMN_NAME_TIMEZONE,
        self::FIELD_TYPE_MOBILE_NUMBER    => self::DICT_COLUMN_NAME_MOBILE_NUMBER,
        self::FIELD_TYPE_ID               => self::DICT_COLUMN_NAME_ID,
        self::FIELD_TYPE_TOKEN            => self::DICT_COLUMN_NAME_TOKEN,
    ];


    const DICT_VALUE_DETECT_BOOLEAN = ['True', 'TRUE', 'true', 'on', 'yes', '1', 1, 0, '0', 'no', 'off', 'False', 'false', 'FALSE'];

    const FORMAT_DATETIME = 'Y-m-d H:i:s';
    const FORMAT_TIME     = 'H:i:s';
    const FORMAT_DATE     = 'Y-m-d';

    const NO_NAME_COLUMN = 'no_name_column';

    //https://www.fakepersongenerator.com/random-florida-address-generator
    const ANONYMOUS_USER = [
        self::FIELD_TYPE_LASTNAME         => 'doe',
        self::FIELD_TYPE_FIRSTNAME        => 'john',
        self::FIELD_TYPE_MAIL             => 'john.doe@unlock-my-data.com',
        self::FIELD_TYPE_ADDRESS          => '99, rue des Dunes',
        self::FIELD_TYPE_SECOND_ADDRESS   => '58, Rue Joseph Vernet',
        self::FIELD_TYPE_ADDRESS_COMPLETE => '99, rue des Dunes batiment B 75000 Paris',
        self::FIELD_TYPE_CITY             => 'Paris',
        self::FIELD_TYPE_COUNTRY          => 'France',
        self::FIELD_TYPE_COUNTRY_CODE     => 'FRA',
        self::FIELD_TYPE_ZIPCODE          => '75000',
        self::FIELD_TYPE_STATE            => 'IDF',
        self::FIELD_TYPE_STATE_FULL       => 'île-de-France',
        self::FIELD_TYPE_PHONE_NUMBER     => '02.13.22.26.20',
        self::FIELD_TYPE_MOBILE_NUMBER    => '03.05.07.94.87',
        self::FIELD_TYPE_TIMEZONE         => 'Europe/Paris',
        self::FIELD_TYPE_LATITUDE         => '28.584480',
        self::FIELD_TYPE_LONGITUDE        => '-81.625229',
    ];

    const transformAccent = [ 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
    'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
    'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ];


    private $sFileName;
    private $sFileType;
    private $bHasHeader;
    private $aHeaderType; // contain the first line of a file if the header is present in the file or the developer can add it
    private $aHeaderData; // contain the first data line and we map with the name of the header
    private $aHeaderByIndex; //column number => column name
    private $aData;
    private $aAlteredData;
    private $aCustomType = [];
    private $aFormatType = [];
    private $sDelimiter;

    public function __construct(string $sFileName, string $sFileType = self::FILE_CSV, bool $bHasHeader = false, array $aHeaderType = [], $sDelimiter = ',')
    {
        HandleLogger::debug(HandleLogger::generateTitle('CONSTRUCT OBJECT MANAGE FILE'));
        HandleLogger::debug('Params (FileName:' . $sFileName . ', FileType:' . $sFileType . ', HasHeader:' . $bHasHeader . ', Header[]: ' . HandleLogger::arrayToString($aHeaderType));

        if (false === $bHasHeader && 0 === count($aHeaderType)) {
            throw new Exception("it's impossible to have to header inside the file and no header given by the developper");
        }

        $this->sFileName   = $sFileName;
        $this->sFileType   = $sFileType;
        $this->bHasHeader  = $bHasHeader;
        $this->aHeaderType = $aHeaderType;
        $this->sDelimiter  = $sDelimiter;
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
            while (($data = fgetcsv($handle, 10000, $this->sDelimiter)) !== false) {
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
                            $sKey = self::NO_NAME_COLUMN;
                            if(true === array_key_exists($nKey, $this->aHeaderByIndex)){
                                $sKey = $this->aHeaderByIndex[$nKey];
                            }
                            $aNextData[$sKey] = $oValue;
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

    private function generateHeader()
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
            //if is a float
            else if (true === filter_var($oValue, FILTER_VALIDATE_FLOAT)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_FLOAT;
            }
            //if is an adress IP
            else if (true === filter_var($oValue, FILTER_VALIDATE_IP)) {
                $this->aHeaderType[$sName] = self::FILED_TYPE_IP;
            } else if (true === filter_var($oValue, FILTER_VALIDATE_INT) || ctype_digit($oValue)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_INT;
            }
            //if is a boolean
            else if (true === in_array($oValue, self::DICT_VALUE_DETECT_BOOLEAN, true)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_BOOLEAN;
            }
            //if a datetime
            else if (false !== DateTime::createFromFormat(self::FORMAT_DATETIME, $oValue)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_DATETIME;
            }
            //if a date
            else if (false !== DateTime::createFromFormat(self::FORMAT_DATE, $oValue)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_DATE;
            }
            //if a time
            else if (false !== DateTime::createFromFormat(self::FORMAT_TIME, $oValue)) {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_TIME;
            } else {
                $this->aHeaderType[$sName] = self::FIELD_TYPE_STRING;
            }

             //analysis with name of the column if we find exatly the same name of column it override the value
             foreach(self::ARRAY_DICT_COLUMN_DETECTOR as $sFieldType => $aDictColumn){
                if (in_array($sName, $aDictColumn)) {
                    $this->aHeaderType[$sName] = $sFieldType;
                }
            }
            //elif is a token
        }

        foreach($this->aCustomType as $sType => $aColumns){
            foreach($aColumns as $sColumnName){
                $this->aHeaderType[$sColumnName] = $sType;
            }
        }

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeaderType'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aHeaderType));

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aHeaderData'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aHeaderData));
    }

    public function addTypeHeader($aColumn, $sType, $sFormat = ''){
        HandleLogger::debug(HandleLogger::generateTitle('DEVELOPPER ADD TYPE HEADER'));

        if(false === array_key_exists($sType, $this->aCustomType)){
            $this->aCustomType[$sType] = [];
        }

        //specially for date or datetime
        if('' !== $sFormat){
                $this->aFormatType[$sType] = $sFormat;            
        }

        $this->aCustomType[$sType] = array_merge($this->aCustomType[$sType], $aColumn);        

        HandleLogger::debug(HandleLogger::generateTitle('OUTPUT aCustomType'));
        HandleLogger::debug(HandleLogger::arrayToString($this->aCustomType));
    }

    public function anonymise($bAlterAllData = false)
    {
        HandleLogger::debug(HandleLogger::generateTitle('ANONYMISE'));

        $this->parseFile();

        if (true === $this->bHasHeader && self::FILE_CSV === $this->sFileType) {
            $this->generateHeader();
        }
        $this->castArrayDataType();
        $this->changePersonnalData($bAlterAllData);
    }

    private function castArrayDataType()
    {
        HandleLogger::debug(HandleLogger::generateTitle('CAST DATA WITH HEADER INFO'));

        foreach ($this->aData as &$oRow) {
            foreach ($oRow as $sName => &$oValue) {
                if (self::FIELD_TYPE_BOOLEAN === $this->aHeaderType[$sName]) {
                    $oValue = (boolean) $oValue;
                }
                //if is a float
                else if (true === in_array($this->aHeaderType[$sName], [self::FIELD_TYPE_FLOAT, self::DICT_COLUMN_NAME_LONGITUDE, self::DICT_COLUMN_NAME_LATITUDE])) {
                    $oValue = (float) $oValue;
                } else if (true === in_array($this->aHeaderType[$sName], [self::FIELD_TYPE_INT, self::FIELD_TYPE_ID])) {
                    $oValue = (int) $oValue;
                } else if (self::FIELD_TYPE_DATETIME === $this->aHeaderType[$sName]) {
                    if(true === array_key_exists($this->aHeaderType[$sName], $this->aFormatType)){
                        $oValue = DateTime::createFromFormat($this->aFormatType[$this->aHeaderType[$sName]], $oValue);
                    }
                    else{
                        $oValue = DateTime::createFromFormat(self::FORMAT_DATETIME, $oValue);
                    }                    
                } else if (self::FIELD_TYPE_DATE === $this->aHeaderType[$sName]) {
                    if(true === array_key_exists($this->aHeaderType[$sName], $this->aFormatType)){
                        $oValue = DateTime::createFromFormat($this->aFormatType[$this->aHeaderType[$sName]], $oValue);
                    }
                    else{
                        $oValue = DateTime::createFromFormat(self::FORMAT_DATE, $oValue);
                    } 
                } else if (self::FIELD_TYPE_TIME === $this->aHeaderType[$sName]) {
                    if(true === array_key_exists($this->aHeaderType[$sName], $this->aFormatType)){
                        $oValue = DateTime::createFromFormat($this->aFormatType[$this->aHeaderType[$sName]], $oValue);
                    }
                    else{
                        $oValue = DateTime::createFromFormat(self::FORMAT_TIME, $oValue);
                    }
                }
            }
        }
        HandleLogger::debug(HandleLogger::generateTitle('VAR_DUMP aData'));
        HandleLogger::debug(HandleLogger::displayType($this->aData));
    }

    /** LITTERELY REPLACE OR CHANGE DATA */
    private function changePersonnalData($bAlterAllData)
    {
        HandleLogger::debug(HandleLogger::generateTitle('REPLACE MAIN DATA RGPD'));
    
        /** Copy aData in aAlteredData */
        $this->aAlteredData = $this->aData;
        /** INIT ARRAY TO COUNT SAME COLUMN TYPE BUT WITH DIFFERENT VALUE 
         * EXEMPLE 
         * FIRSTNAME ROW 1 : TOM => JOHN 
         * FIRSTNAME ROW 2 : JEAN => JONH_2 
         * */
        $aOccurrenceSameTypeColumn = [];

        foreach($this->aData as $nIndex => $aRow)
        {
            foreach($aRow as $sName => $oValue){

                $sAnonymData = $oValue;

                //check if we have a convertion of value and if we did'nt convert it
                if(true === array_key_exists($this->aHeaderType[$sName], self::ANONYMOUS_USER) && $oValue !== self::ANONYMOUS_USER[$this->aHeaderType[$sName]]){
                
                    $sAnonymData = self::ANONYMOUS_USER[$this->aHeaderType[$sName]];
                    
                    //check if we haven't convert other value
                    if(false === array_key_exists($this->aHeaderType[$sName], $aOccurrenceSameTypeColumn)){
                        $aOccurrenceSameTypeColumn[$this->aHeaderType[$sName]] = 1;
                    }
                    else{
                        $aOccurrenceSameTypeColumn[$this->aHeaderType[$sName]]++;
                        $sAnonymData .= '_' . $aOccurrenceSameTypeColumn[$this->aHeaderType[$sName]];
                    }
                }
                else if(true === $bAlterAllData){ // if alter all data is actived we need to generate an anonymous data according to the type of the column
                    if (self::FIELD_TYPE_ID === $this->aHeaderType[$sName]){
                        if(true === is_int($oValue)){
                            $sAnonymData = $oValue * rand(10,40);
                        }
                    }
                    else if(self::FIELD_TYPE_TOKEN === $this->aHeaderType[$sName]){
                        // get larger to take one caracter
                        $nLengthValue = strlen($oValue);
                        //choose one number in the size of the value
                        $nItemKey = rand(0, $nLengthValue - 1);
                        
                        $sLetterChoose = $oValue[$nItemKey];
                        $sReplaceLetter = chr(rand(65,90));

                        while($sLetterChoose == $sReplaceLetter){
                            $sReplaceLetter = chr(rand(65,90));
                        }
                        $sAnonymData = str_replace($sLetterChoose, $sReplaceLetter, $oValue);
                    }
                    else if($oValue instanceof DateTime){
                        $oAnonymData = $oValue;
                        $oAnonymData->modify('+'.rand(1,30).' day');
                        $sFormatDate = '';
                        if(true === array_key_exists($this->aHeaderType[$sName], $this->aFormatType)){
                            $sFormatDate = $this->aFormatType[$this->aHeaderType[$sName]];
                        }
                        else if (self::FIELD_TYPE_DATETIME === $this->aHeaderType[$sName]){
                            $sFormatDate = self::FORMAT_DATETIME;
                        }
                        else if (self::FIELD_TYPE_DATE === $this->aHeaderType[$sName]){
                            $sFormatDate = self::FORMAT_DATE;
                        }
                        else if (self::FIELD_TYPE_TIME === $this->aHeaderType[$sName]){
                            $sFormatDate = self::FORMAT_TIME;
                        }
                        $sAnonymData = $oAnonymData->format($sFormatDate);
                    }
                }
                    
                if($this->aAlteredData[$nIndex][$sName] === $oValue){
                    
                    $this->aAlteredData[$nIndex][$sName] = $sAnonymData;

                    if(self::FIELD_TYPE_ID !== $this->aHeaderType[$sName]){
                        /** TODO IF you have a better idea.... */
                        foreach ($this->aAlteredData[$nIndex] as $sAlterName => &$oAlterValue) {
                            
                            $sValueCompare = $oValue;
                            if($sValueCompare instanceof DateTime){
                                $sValueCompare = $sValueCompare->date;
                            }

                            if(true === is_string($oAlterValue)){
                                // replace same value
                                $oAlterValue = str_replace($sValueCompare,$sAnonymData,$oAlterValue);
                            
                                $oAlterValue = str_replace(trim($sValueCompare),$sAnonymData,$oAlterValue);

                                // handle lowCase
                                $oAlterValue = str_replace(strtolower($sValueCompare),strtolower($sAnonymData),$oAlterValue);
                                $oAlterValue = str_replace(strtolower(trim($sValueCompare)),strtolower($sAnonymData),$oAlterValue);

                                // handle upCase
                                $oAlterValue = str_replace(strtoupper($sValueCompare),strtoupper($sAnonymData),$oAlterValue);
                                $oAlterValue = str_replace(strtoupper(trim($sValueCompare)),strtoupper($sAnonymData),$oAlterValue);

                                /** DELETE ACCENT */
                                $oValueWithoutAccent =  strtr($sValueCompare, self::transformAccent);
                                $sAnonymDataWithoutAccent = strtr($sAnonymData, self::transformAccent);

                                // replace same value
                                $oAlterValue = str_replace($oValueWithoutAccent,$sAnonymDataWithoutAccent,$oAlterValue);
                            
                                // handle lowCase
                                $oAlterValue = str_replace(strtolower($oValueWithoutAccent),strtolower($sAnonymDataWithoutAccent),$oAlterValue);
                                
                                // handle upCase
                                $oAlterValue = str_replace(strtoupper($oValueWithoutAccent),strtoupper($sAnonymDataWithoutAccent),$oAlterValue);
                            
                            }
                        }   
                    }   
                }     
            }
        }

        HandleLogger::debug(HandleLogger::generateTitle('VAR_DUMP aAlteredData'));
        HandleLogger::debug(HandleLogger::displayType($this->aAlteredData));
    }

    public function generateFile($sPrefix = 'john_doe_')
    {
        HandleLogger::debug(HandleLogger::generateTitle('GENERATE - EXPORT FILE'));

        $sDestFile = $sPrefix . $this->sFileName;

        $oDestFile = fopen($sDestFile, 'w');

        /** IF THE FILE HAS THE HEADER WE NEED TO PUT IT INSIDE */
        fputcsv($oDestFile, $this->aHeaderByIndex);

        foreach ($this->aAlteredData as $oValue) {
            fputcsv($oDestFile, $oValue);
        }

        fclose($oDestFile);

        HandleLogger::debug('Export File Name : ' . $sDestFile);
    }
}
