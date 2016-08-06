<?php
namespace IComeFromTheNet\VoucherNum\Test;

use DateTime;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;
use IComeFromTheNet\VoucherNum\VoucherException;


class VoucherOperationsTest extends VoucherTestAbstract
{
    
    public function getDataSet()
    {
       return new ArrayDataSet([
           __DIR__.'/Fixture/VoucherFixture.php',
        ]);
    }
    
    public function testVoucherGroup()
    {
        
        $oContainer = $this->getContainer();
        $oCommandBus = $this->getContainer()->getCommandBus();
        
        
        # test successful
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\CreateVoucherGroupCommand();
        $sName = 'Sales Vouchers';
        $bDisabled = true;
        $iSort = 100;
        $sSlugName = 'sales_vouchers';
        
        $oCommand->setDisabledStatus($bDisabled);
        $oCommand->setVoucherGroupName($sName);
        $oCommand->setSortOrder($iSort);
        $oCommand->setSlugName($sSlugName);
    
        
        $oCommandBus->handle($oCommand);
        $iNewVoucherGroupId = $oCommand->getVoucherGroupId();
        $this->assertNotEmpty($iNewVoucherGroupId);
        
        
        // Test if update a group
        
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\ReviseVoucherGroupCommand();
        
        $sName = 'Sales Vouchers Next';
        $bDisabled = false;
        $iSort = 100;
        $sSlugName = 'sales_vouchers_next';
       
        $oCommand->setDisabledStatus($bDisabled);
        $oCommand->setVoucherGroupName($sName);
        $oCommand->setSlugName($sSlugName);
        $oCommand->setSortOrder($iSort);
        $oCommand->setVoucherGroupId($iNewVoucherGroupId);
        
        $oCommandBus->handle($oCommand);
    
        // no exception thrown
        $this->assertTrue(true);
        
        
        // Test if remove a group
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\RemoveVoucherGroupCommand();
        
        $oCommand->setVoucherGroupId($iNewVoucherGroupId);
    
        $oCommandBus->handle($oCommand);
        
        // no exception thrown    
        $this->assertTrue(true);
        
    
    }
    
    
   
     public function testVoucherRule()
     {
        
        $oContainer = $this->getContainer();
        $oCommandBus = $this->getContainer()->getCommandBus();
        
        $oRuleCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\CreateVoucherRuleCommand();
        
        $oRuleCommand->setVoucherRuleName('Rule A');
        $oRuleCommand->setSlugRuleName('rule_a');
        $oRuleCommand->setVoucherPaddingCharacter('a');
        $oRuleCommand->setVoucherSuffix('_rule');
        $oRuleCommand->setVoucherPrefix('my_');
        $oRuleCommand->setVoucherLength(5);
        $oRuleCommand->setSequenceStrategyName('SEQUENCE');
        
        $oCommandBus->handle($oRuleCommand);    
        
        $iRuleId = $oRuleCommand->getVoucherGenRuleId();
        
        $this->assertNotEmpty($iRuleId);
        
          // test rule Update
        
    
        $oRuleCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\ReviseVoucherRuleCommand();
       
        
         $oRuleCommand->setVoucherRuleName('Rule A');
         $oRuleCommand->setSlugRuleName('rule_a');
         $oRuleCommand->setVoucherPaddingCharacter('a');
         $oRuleCommand->setVoucherSuffix('_rule');
         $oRuleCommand->setVoucherPrefix('my_');
         $oRuleCommand->setVoucherLength(5);
         $oRuleCommand->setSequenceStrategyName('SEQUENCE');
         $oRuleCommand->setVoucherGenRuleId($iRuleId);      
        
        
         $oCommandBus->handle($oRuleCommand);    
        
         // no exception thrown
         $this->assertTrue(true);
   
        
        
    }
    
   
    
     public function testVoucherType()
     {
        $oContainer = $this->getContainer();
        $oCommandBus = $this->getContainer()->getCommandBus();
           
        $iVoucherTypeId = 1;
        $sName          ='test voucher';
        $sSlugName      ='test_voucher';
        $sDescription   = 'A sucessful test voucher';
        $oEnableFrom    = new DateTime();
        $oEnableTo      = new DateTime('NOW + 5 days');
        $iVoucherGroupId = 1;
        $iVoucherGenRuleId =1;
        
        // Test New
        
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherType\Command\NewVoucherTypeCommand();
     
        
        $oCommand->setSlug($sSlugName);
        $oCommand->setName($sName);
        $oCommand->setDescription($sDescription);
        $oCommand->setEnabledFrom($oEnableFrom);
        $oCommand->setVoucherGroupId($iVoucherGroupId);
        $oCommand->setVoucherGenruleId($iVoucherGenRuleId);
        
        $oCommandBus->handle($oCommand);
        
        $iNewVoucherTypeId = $oCommand->getVoucherTypeId();
       
        $this->assertNotEmpty($iNewVoucherTypeId);
     
     
        // test update
     
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ReviseVoucherTypeCommand();
         
        $oCommand->setSlug($sSlugName);
        $oCommand->setName($sName);
        $oCommand->setDescription($sDescription);
        $oCommand->setEnabledFrom($oEnableFrom);
        $oCommand->setVoucherGroupId($iVoucherGroupId);
        $oCommand->setVoucherGenruleId($iVoucherGenRuleId);
        $oCommand->setDescription('an updated description');
        $oCommand->setVoucherTypeId($iNewVoucherTypeId);
     
        $oCommandBus->handle($oCommand);
        
        $this->assertTrue(true);
     
     
        // test delete no last date given
      
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ExpireVoucherTypeCommand();
     
        $oCommand->setEnabledTo(null);
        $oCommand->setEnabledFrom($oEnableFrom);
        $oCommand->setVoucherTypeId($iNewVoucherTypeId);
        $oCommand->setSlug($sSlugName);
        $oCommand->setName($sName);
        $oCommand->setDescription($sDescription);
        $oCommand->setVoucherGroupId($iVoucherGroupId);
        $oCommand->setVoucherGenruleId($iVoucherGenRuleId);
        $oCommand->setDescription('an updated description');
     
        $oCommandBus->handle($oCommand);
        
        $this->assertTrue(true);
        $this->assertEquals($oContainer->getNow(),$oCommand->getEnabledTo());
        
        // Test delete if last date give
        $oCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ExpireVoucherTypeCommand();
     
        $oCommand->setEnabledTo($oEnableTo);
        $oCommand->setEnabledFrom($oEnableFrom);
        $oCommand->setVoucherTypeId($iNewVoucherTypeId);
        $oCommand->setSlug($sSlugName);
        $oCommand->setName($sName);
        $oCommand->setDescription($sDescription);
        $oCommand->setVoucherGroupId($iVoucherGroupId);
        $oCommand->setVoucherGenruleId($iVoucherGenRuleId);
        $oCommand->setDescription('an updated description');
     
     
        $oCommandBus->handle($oCommand);
        
        $this->assertTrue(true);
     
        $this->assertEquals($oEnableTo,$oCommand->getEnabledTo());

    }
    
    
     public function testVoucherInstanceCreate()
     {
        $oContainer = $this->getContainer();
        $oCommandBus = $this->getContainer()->getCommandBus();
        
        $oRuleCommand = new \IComeFromTheNet\VoucherNum\Model\VoucherInstance\Command\CreateVoucherCommand();
        
        $oRuleCommand->setVoucherTypeId(1);
        $oRuleCommand->setVoucherCode('aaa_01_01');
        
        $oCommandBus->handle($oRuleCommand);    
        
        
        $this->assertNotEmpty($oRuleCommand->getVoucherInstanceId());
     }
    
    
}
/* End of class */