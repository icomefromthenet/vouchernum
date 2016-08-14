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
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationException;
use IComeFromTheNet\VoucherNum\VoucherGenerator;


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


try {

$oDatabase->beginTransaction();

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

$oVoucherRuleOne   = new CreateVoucherRuleCommand();
$oVoucherRuleTwo  = new CreateVoucherRuleCommand();
$oVoucherRuleThree = new CreateVoucherRuleCommand();
$oVoucherRuleFour = new CreateVoucherRuleCommand();
$oVoucherRuleFive = new CreateVoucherRuleCommand();


$oVoucherRuleOne->setVoucherRuleName('Rule A');
$oVoucherRuleOne->setSlugRuleName('rule_a');
$oVoucherRuleOne->setVoucherPaddingCharacter('#');
$oVoucherRuleOne->setVoucherSuffix('');
$oVoucherRuleOne->setVoucherPrefix('A');
$oVoucherRuleOne->setVoucherLength(8);
$oVoucherRuleOne->setSequenceStrategyName('sequence');
$oVoucherRuleOne->setValidationRules(array('always-valid'));
     
$oVoucherRuleTwo->setVoucherRuleName('Rule B');
$oVoucherRuleTwo->setSlugRuleName('rule_b');
$oVoucherRuleTwo->setVoucherPaddingCharacter('#');
$oVoucherRuleTwo->setVoucherSuffix('');
$oVoucherRuleTwo->setVoucherPrefix('B');
$oVoucherRuleTwo->setVoucherLength(8);
$oVoucherRuleTwo->setSequenceStrategyName('sequence');
$oVoucherRuleTwo->setValidationRules(array('always-valid'));

$oVoucherRuleThree->setVoucherRuleName('Rule C');
$oVoucherRuleThree->setSlugRuleName('rule_c');
$oVoucherRuleThree->setVoucherPaddingCharacter('#');
$oVoucherRuleThree->setVoucherSuffix('');
$oVoucherRuleThree->setVoucherPrefix('C');
$oVoucherRuleThree->setVoucherLength(8);
$oVoucherRuleThree->setSequenceStrategyName('sequence');
$oVoucherRuleThree->setValidationRules(array('always-valid'));

$oVoucherRuleFour->setVoucherRuleName('Rule D');
$oVoucherRuleFour->setSlugRuleName('rule_d');
$oVoucherRuleFour->setVoucherPaddingCharacter('#');
$oVoucherRuleFour->setVoucherSuffix('');
$oVoucherRuleFour->setVoucherPrefix('D');
$oVoucherRuleFour->setVoucherLength(8);
$oVoucherRuleFour->setSequenceStrategyName('sequence');
$oVoucherRuleFour->setValidationRules(array('always-valid'));

$oVoucherRuleFive->setVoucherRuleName('Rule E');
$oVoucherRuleFive->setSlugRuleName('rule_e');
$oVoucherRuleFive->setVoucherPaddingCharacter('#');
$oVoucherRuleFive->setVoucherSuffix('');
$oVoucherRuleFive->setVoucherPrefix('E');
$oVoucherRuleFive->setVoucherLength(8);
$oVoucherRuleFive->setSequenceStrategyName('sequence');
$oVoucherRuleFive->setValidationRules(array('always-valid'));


$oContainer->getCommandBus()->handle($oVoucherRuleOne);
$oContainer->getCommandBus()->handle($oVoucherRuleTwo);
$oContainer->getCommandBus()->handle($oVoucherRuleThree);
$oContainer->getCommandBus()->handle($oVoucherRuleFour);
$oContainer->getCommandBus()->handle($oVoucherRuleFive);



// Create Voucher Types
$oVoucherTypeOne = new NewVoucherTypeCommand();
$oVoucherTypeTwo = new NewVoucherTypeCommand();
$oVoucherTypeThree = new NewVoucherTypeCommand();
$oVoucherTypeFour = new NewVoucherTypeCommand();
$oVoucherTypeFive = new NewVoucherTypeCommand();

$oVoucherTypeOne->setName('Sales Journal');
$oVoucherTypeOne->setSlug('sales_journal');
$oVoucherTypeOne->setDescription('a short description');
$oVoucherTypeOne->setEnabledFrom($oNow);
$oVoucherTypeOne->setVoucherGroupId($oGroupTwo->getVoucherGroupId());
$oVoucherTypeOne->setVoucherGenruleId($oVoucherRuleOne->getVoucherGenRuleId());

$oVoucherTypeTwo->setName('COD Journal');
$oVoucherTypeTwo->setSlug('cod_journal');
$oVoucherTypeTwo->setDescription('a short description');
$oVoucherTypeTwo->setEnabledFrom($oNow);
$oVoucherTypeTwo->setVoucherGroupId($oGroupOne->getVoucherGroupId());
$oVoucherTypeTwo->setVoucherGenruleId($oVoucherRuleTwo->getVoucherGenRuleId());


$oVoucherTypeThree->setName('Returns Journal');
$oVoucherTypeThree->setSlug('returns_journal');
$oVoucherTypeThree->setDescription('a short description');
$oVoucherTypeThree->setEnabledFrom($oNow);
$oVoucherTypeThree->setVoucherGroupId($oGroupTwo->getVoucherGroupId());
$oVoucherTypeThree->setVoucherGenruleId($oVoucherRuleThree->getVoucherGenRuleId());


$oVoucherTypeFour->setName('Purchases Journal');
$oVoucherTypeFour->setSlug('purchases_journal');
$oVoucherTypeFour->setDescription('a short description');
$oVoucherTypeFour->setEnabledFrom($oNow);
$oVoucherTypeFour->setVoucherGroupId($oGroupOne->getVoucherGroupId());
$oVoucherTypeFour->setVoucherGenruleId($oVoucherRuleFour->getVoucherGenRuleId());


$oVoucherTypeFive->setName('Receipts Journal');
$oVoucherTypeFive->setSlug('receipts_journal');
$oVoucherTypeFive->setDescription('a short description');
$oVoucherTypeFive->setEnabledFrom($oNow);
$oVoucherTypeFive->setVoucherGroupId($oGroupTwo->getVoucherGroupId());
$oVoucherTypeFive->setVoucherGenruleId($oVoucherRuleFive->getVoucherGenRuleId());



$oContainer->getCommandBus()->handle($oVoucherTypeOne);
$oContainer->getCommandBus()->handle($oVoucherTypeTwo);
$oContainer->getCommandBus()->handle($oVoucherTypeThree);
$oContainer->getCommandBus()->handle($oVoucherTypeFour);
$oContainer->getCommandBus()->handle($oVoucherTypeFive);

} catch(ValidationException $e) {
    var_dump($e->getValidationFailures());
    $oDatabase->rollback();
    exit;
}

$oDatabase->commit();

//--------------------------------------------------------------------------------
// Generate Vouchers
//--------------------------------------------------------------------------------

$oGeneratorOne = new VoucherGenerator($oContainer);
$oGeneratorTwo = new VoucherGenerator($oContainer);
$oGeneratorThree = new VoucherGenerator($oContainer);
$oGeneratorFour = new VoucherGenerator($oContainer);
$oGeneratorFive = new VoucherGenerator($oContainer);

$oGeneratorOne->setVoucherById($oVoucherTypeOne->getVoucherTypeId());
$oGeneratorTwo->setVoucherById($oVoucherTypeTwo->getVoucherTypeId());
$oGeneratorThree->setVoucherById($oVoucherTypeThree->getVoucherTypeId());
$oGeneratorFour->setVoucherById($oVoucherTypeFour->getVoucherTypeId());
$oGeneratorFive->setVoucherById($oVoucherTypeFive->getVoucherTypeId());

for($i=0; $i < 100; $i++) {
    
    $oDatabase->beginTransaction();
    
    echo $oGeneratorOne->generate()   .' '. $oGeneratorTwo->generate() .' ';
    echo $oGeneratorThree->generate() .' '. $oGeneratorFour->generate() .' ';
    echo $oGeneratorFive->generate()  .' ';
    echo PHP_EOL;
    
    $oDatabase->commit();
    
}


