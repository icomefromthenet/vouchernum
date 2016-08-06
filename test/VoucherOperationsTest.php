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
    
    
   
    //  /**
    //  * @expectedException        IComeFromTheNet\VoucherNum\VoucherException
    //  * @expectedExceptionMessage Unable to create new voucher rule the Entity has a database id assigned already
    //  */
    // public function testVoucherRule()
    // {
        
    //     $oContainer = $this->getContainer();
    //     $aOperations = $oContainer->getVoucherRuleOperations();
        
    //     $oOperation = $aOperations['create'];
    
    //     # assert correct operation was returned
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\RuleCreate',$oOperation);
     
        
    //     $oRule = new VoucherGenRule();
        
    //     $oRule->setVoucherRuleName('Rule A');
    //     $oRule->setSlugRuleName('rule_a');
    //     $oRule->setVoucherPaddingCharacter('a');
    //     $oRule->setVoucherSuffix('_rule');
    //     $oRule->setVoucherPrefix('my_');
    //     $oRule->setVoucherLength(5);
    //     $oRule->setSequenceStrategyName('SEQUENCE');
        
    //     $oOperation->execute($oRule);
        
    //     $this->assertNotEmpty($oRule->getVoucherGenRuleId());
        
    //     // test rule Update
        
    //     $oOperation = $aOperations['update'];
    
    //     # assert correct operation was returned
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\RuleRevise',$oOperation);
        
    //     $oRule->setVoucherRuleName('Rule A');
    //     $oRule->setSlugRuleName('rule_a');
    //     $oRule->setVoucherPaddingCharacter('a');
    //     $oRule->setVoucherSuffix('_rule');
    //     $oRule->setVoucherPrefix('my_');
    //     $oRule->setVoucherLength(5);
    //     $oRule->setSequenceStrategyName('SEQUENCE');
     
        
    //     $oOperation->execute($oRule);
        
    //     $this->assertNotEmpty($oRule->getVoucherGenRuleId());
        
    //     // test can create exsting entity
        
    //     $oOperation = $aOperations['create'];
         
    //     $oOperation->execute($oRule);
        
    // }
    
   
    
    // public function testVoucherType()
    // {
    //     $oContainer = $this->getContainer();
    //     $aOperations = $oContainer->getVoucherTypeOperations();
        
    //     $oOperation = $aOperations['create'];
    
    //     # assert correct operation was returned
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\TypeCreate',$oOperation);
        
    //     $oType = new VoucherType();  
        
    //      $iVoucherTypeId = 1;
    //     $sName          ='test voucher';
    //     $sSlugName      ='test_voucher';
    //     $sDescription   = 'A sucessful test voucher';
    //     $oEnableFrom    = new DateTime();
    //     $oEnableTo      = new DateTime('NOW + 5 days');
    //     $iVoucherGroupId = 1;
    //     $iVoucherGenRuleId =1;
        
        
    //     $oType->setSlug($sSlugName);
    //     $oType->setName($sName);
    //     $oType->setDescription($sDescription);
    //     $oType->setEnabledFrom($oEnableFrom);
    //     $oType->setVoucherGroupId($iVoucherGroupId);
    //     $oType->setVoucherGenruleId($iVoucherGenRuleId);
        
    //     $oOperation->execute($oType);
        
    //     $this->assertNotEmpty($oType->getVoucherTypeId());
     
     
    //     // test update
     
    //     $aOperations = $oContainer->getVoucherTypeOperations();
        
    //     $oOperation = $aOperations['update'];
    
    //     # assert correct operation was returned
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\TypeRevise',$oOperation);
     
    //     $oType->setDescription('an updated description');
        
    //     $this->assertTrue($oOperation->execute($oType));
     
    //   // test delete no last date given
      
    //     $oOperation = $aOperations['delete'];
    //     $oType->setEnabledTo(null);
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\TypeExpire',$oOperation);
     
    //     $bResult = $oOperation->execute($oType);
    //     //var_dump($oContainer['TestQueryLog']->lastQuery());
        
    //     $this->assertTrue($bResult);
    //     $this->assertEquals($oContainer->getNow(),$oType->getEnabledTo());
        
    //     // Test delete if last date give
    //     $oOperation = $aOperations['delete'];
    //     $oType->setEnabledTo($oEnableTo);
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\TypeExpire',$oOperation);
     
    //     $bResult = $oOperation->execute($oType);
    //     $this->assertTrue($bResult);
    //     $this->assertEquals($oEnableTo,$oType->getEnabledTo());

    // }
    
    
    // public function testVoucherInstanceCreate()
    // {
    //     $oContainer = $this->getContainer();
    //     $aOperations = $oContainer->getVoucherInstanceOperations();
        
    //     $oOperation = $aOperations['create'];
    
    //     # assert correct operation was returned
    //     $this->assertInstanceOf('\IComeFromTheNet\VoucherNum\Operations\VoucherCreate',$oOperation);
        
    //     $oVoucher = new VoucherInstance();
        
    //     $oVoucher->setVoucherTypeId(1);
    //     $oVoucher->setVoucherCode('aaa_01_01');
         
    //     $this->assertTrue($oOperation->execute($oVoucher));
        
    //     $this->assertNotEmpty($oVoucher->getVoucherInstanceId());
    // }
    
    
}
/* End of class */