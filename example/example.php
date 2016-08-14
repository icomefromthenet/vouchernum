<?php
//--------------------------------------------------------------------------------
// Auloader 
//--------------------------------------------------------------------------------

require '../vendor//autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Doctrine\DBAL\Schema\Schema;
use DBALGateway\Table\GatewayProxyCollection;

use IComeFromTheNet\VoucherNum\VoucherContainer;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\CreateVoucherRuleCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\CreateVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\NewVoucherTypeCommand;


//--------------------------------------------------------------------------------
// Setup External Dep 
//--------------------------------------------------------------------------------

$oLogger =  new Logger('test-ledger',array(new TestHandler()));
$oEvent  =  new EventDispatcher();
$oNow    =  new DateTime();

//--------------------------------------------------------------------------------
// Setup Database Connection
//--------------------------------------------------------------------------------

$aConfig = include '../database/config/default.php';


$config = new \Doctrine\DBAL\Configuration();
    
    $connectionParams = array(
        'dbname' => $aConfig[0]['schema']
        ,'user' => $aConfig[0]['user']
        ,'password' => $aConfig[0]['password']
        ,'host' => $aConfig[0]['host']
        ,'driver' => $aConfig[0]['type']
        ,'port'   =>$aConfig[0]['port']
    );

$oDatabase = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

$oDatabase->connect();

$oGatewayProxy = new GatewayProxyCollection(new \Doctrine\DBAL\Schema\Schema());

//--------------------------------------------------------------------------------
// Create the Project Container
//--------------------------------------------------------------------------------
$oContainer = new VoucherContainer($oDatabase, $oEvent, $oLogger, $oGatewayProxy);

$oContainer->boot($oNow);


//--------------------------------------------------------------------------------
// Setup Example Vouchers
//--------------------------------------------------------------------------------


// Create some Voucher Groups

$oGroupOne    = new CreateVoucherGroupCommand();    
$oGroupTwo    = new CreateVoucherGroupCommand();
$oGroupThree  = new CreateVoucherGroupCommand();

$oGroupOne->setVoucherGroupName('Group One');
$oGroupOne->setSlugName('group_one');
$oGroupOne->setSortOrder(1);
$oGroupOne->setDisabledStatus(false);


$oGroupTwo->setVoucherGroupName('Group Two');
$oGroupTwo->setSlugName('group_two');
$oGroupTwo->setSortOrder(2);
$oGroupTwo->setDisabledStatus(false);

$oGroupThree->setVoucherGroupName('Group Three');
$oGroupThree->setSlugName('group_three');
$oGroupThree->setSortOrder(3);
$oGroupThree->setDisabledStatus(false);

$oContainer->getCommandBus()->handle($oGroupOne);
$oContainer->getCommandBus()->handle($oGroupTwo);
$oContainer->getCommandBus()->handle($oGroupThree);


// Create Some Voucher Gen Rules




// Create Voucher Types




//--------------------------------------------------------------------------------
// Generate Vouchers
//--------------------------------------------------------------------------------


