<?php

/********************* INCLUDE **************************/
include("ManageFile.php");
/********************** END INCLUDE ********************/


/************* PARAMS **********************************/
$sFileName = 'part.csv';
$bFileType = ManageFile::FILE_CSV;
$bHasHeader = true;
/************* END PARAMS *****************************/

$oManage = new ManageFile($sFileName, $bFileType, $bHasHeader, [], ',');

/** SAMSUNG GDPR */

//'Ymd'
$oManage->addTypeHeader(['INSERT_DATEDAY_KEY (Dimension de ciblage/Informations techniques)', 'UPDATE_DATEDAY_KEY (Dimension de ciblage/Informations techniques)'], ManageFile::FIELD_TYPE_DATE, 'Ymd');
//'Y/m/d H:i:s'
$oManage->addTypeHeader(['Date dernière promo (Dimension de ciblage)','Date de modification source (Dimension de ciblage)','Date de création source (Dimension de ciblage)','Date de création dans Neolane (Dimension de ciblage)','Date d\'achat de produit la plus récente (Dimension de ciblage)','INSERT_FULLDATE (Dimension de ciblage/Informations techniques)', 'UPDATE_FULLDATE (Dimension de ciblage/Informations techniques)'], ManageFile::FIELD_TYPE_DATETIME, 'Y/m/d H:i:s');
//H:i:s
$oManage->addTypeHeader(['INSERT_DAYTIME_KEY (Dimension de ciblage/Informations techniques)', 'UPDATE_DAYTIME_KEY (Dimension de ciblage/Informations techniques)'], ManageFile::FIELD_TYPE_TIME, 'H:i:s');

//'Y/m/d H:i:s'
$oManage->addTypeHeader(['Date dernière ouverture (Dimension de ciblage)', 'Date dernier Email abouti (Dimension de ciblage)','Date collecte (Dimension de ciblage)','Dernier produit possédé (Dimension de ciblage)','Date dernier achat smartphone (Dimension de ciblage/Informations sur les récences)','Date dernier achat tablette (Dimension de ciblage/Informations sur les récences)','﻿Date de Création (Dimension de ciblage)', 'Date de modification (Dimension de ciblage)', 'Date de participation (Dimension de ciblage)'], ManageFile::FIELD_TYPE_DATETIME, 'Y/m/d H:i:s');
//'d/m/Y'
$oManage->addTypeHeader(['Texte 3 (Dimension de ciblage)'], ManageFile::FIELD_TYPE_DATE, 'd/m/Y');
//'Y/m/d'
$oManage->addTypeHeader(['Date de naissance (Dimension de ciblage)'], ManageFile::FIELD_TYPE_DATE, 'Y/m/d');

/** UDEMY GDPR */
/*$oManage->addTypeHeader(['title', 'email'], ManageFile::FIELD_TYPE_TOKEN);
$oManage->addTypeHeader(['locationid'], ManageFile::FIELD_TYPE_ID);
$oManage->addTypeHeader(['mutable_time_zone'], ManageFile::FIELD_TYPE_TIMEZONE);
$oManage->addTypeHeader(['created', 'modified', 'last_login'], ManageFile::FIELD_TYPE_DATETIME, 'm/d/y H:i');*/

$oManage->anonymise(true);

$oManage->generateFile();

?>