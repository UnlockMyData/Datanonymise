<?php 
    class HandleLogger{

        /** WEB PAGE MODE */
        const RETURN_LINE = "<br />";
        /** CONSOLE MODE */
        //const RETURN_LINE = "\n";

        public static function error(string $sMessage):void{
            echo $sMessage.self::RETURN_LINE;
        }

        public static function debug(string $sMessage):void{
            echo $sMessage.self::RETURN_LINE;
        }

        public static function generateTitle(string $sTitle):string{
            return '***************** '.$sTitle.' *****************'.self::RETURN_LINE;
        }

        public static function arrayToString(array $aData){
            return json_encode($aData).self::RETURN_LINE;
        }

    }

?>