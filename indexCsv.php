<?php

/********************* INCLUDE **************************/
include("ManageFile.php");
/********************** END INCLUDE ********************/


/************* PARAMS **********************************/
$sFileName = 'first.csv';
$bFileType = ManageFile::FILE_CSV;
$bHasHeader = true;
/************* END PARAMS *****************************/
$oManage = new ManageFile($sFileName, $bFileType, $bHasHeader);

$oManage->anonymise();

$oManage->generateFile();


?>