<?php
require_once __DIR__."/../../autoload.php";

use Mouf\Actions\InstallUtils;
use Mouf\MoufManager;

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("jQueryLibrary")) {
	$jQueryLib = $moufManager->getInstanceDescriptor("jQueryLibrary");
} else {
	$jQueryLib = $moufManager->createInstance("\Mouf\Html\Utils\WebLibraryManager\WebLibrary");
	$jQueryLib->setName("jQueryLibrary");
}
$jQueryLib->getProperty("jsFiles")->setValue(array(
	'vendor/mouf/javascript.jquery.jquery/jquery-1.7.2.min.js'
));
$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
$jQueryLib->getProperty("renderer")->setValue($renderer);

$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $jQueryLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();