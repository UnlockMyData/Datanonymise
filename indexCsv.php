<?php

/********************* INCLUDE **************************/
include("ManageFile.php");
/********************** END INCLUDE ********************/


/************* PARAMS **********************************/
$sFileName = 'General User Info.csv';
$bFileType = ManageFile::FILE_CSV;
$bHasHeader = true;
/************* END PARAMS *****************************/

$oManage = new ManageFile($sFileName, $bFileType, $bHasHeader);

$oManage->addTypeHeader(['title', 'email'], ManageFile::FIELD_TYPE_TOKEN);
$oManage->addTypeHeader(['locationid'], ManageFile::FIELD_TYPE_ID);
$oManage->addTypeHeader(['mutable_time_zone'], ManageFile::FIELD_TYPE_TIMEZONE);
$oManage->addTypeHeader(['created', 'modified', 'last_login'], ManageFile::FIELD_TYPE_DATETIME, 'm/d/y H:i');

$oManage->anonymise(true);

$oManage->generateFile();

?>