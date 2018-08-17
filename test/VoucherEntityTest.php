<?php
namespace IComeFromTheNet\VoucherNum\Test;

use DateTime;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroup;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRule;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\VoucherInstance;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;
use PHPUnit\Framework\TestCase;

/**
  *  Test the Voucher Entity Object
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherEntityTest extends TestCase
{
    
    
    public function testVoucherGroupProperty()
    {
        
        $aGroup = new VoucherGroup();
        
        $sName = 'Sales Vouchers';
        $iID   = 1;
        $bDisabled = false;
        $iSort = 100;
        $oCreated = new DateTime();
        $sSlugName = 'sales_vouchers';
        
        $aGroup->setVoucherGroupID($iID);
        $this->assertEquals($iID,$aGroup->getVoucherGroupID());
        
        $aGroup->setDisabledStatus($bDisabled);
        $this->assertEquals($bDisabled,$aGroup->getDisabledStatus());
        
        $aGroup->setVoucherGroupName($sName);
        $this->assertEquals($sName,$aGroup->getVoucherGroupName());
        
        $aGroup->setSortOrder($iSort);
        $this->assertEquals($iSort,$aGroup->getSortOrder());
        
        $aGroup->setDateCreated($oCreated);
        $this->assertEquals($oCreated,$aGroup->getDateCreated());
        
        $aGroup->setSlugName($sSlugName);
        $this->assertEquals($sSlugName,$aGroup->getSlugName());
        
    }
    
   
    
    
    public function testVoucherRuleProperty()
    {
        $oRule = new VoucherGenRule();
        
        $iVoucherGeneratorRuleId   = 1;
        $sVoucherRuleNameSlug      = 'rule_1';
        $sVoucherRuleName          = 'Rule 1';
        $sVoucherPaddingCharacter  = 'A'; 
        $sVoucherSuffix            = '###';
        $sVoucherPrefix            = '@@@';
        $iVoucherLength            = 10;
        $oDateCreated              = new DateTime();    
        $sSequenceStrategy         = 'UUID';
        
        
        $oRule->setVoucherGenRuleId($iVoucherGeneratorRuleId);
        $oRule->setSlugRuleName($sVoucherRuleNameSlug);
        $oRule->setVoucherRuleName($sVoucherRuleName);
        $oRule->setVoucherPaddingCharacter($sVoucherPaddingCharacter);
        $oRule->setVoucherSuffix($sVoucherSuffix);
        $oRule->setVoucherPrefix($sVoucherPrefix);
        $oRule->setVoucherLength($iVoucherLength);
        $oRule->setDateCreated($oDateCreated);
        $oRule->setSequenceStrategyName($sSequenceStrategy);
        
        $this->assertEquals($iVoucherGeneratorRuleId ,$oRule->getVoucherGenRuleId());
        $this->assertEquals($sVoucherRuleNameSlug,$oRule->getSlugRuleName());
        $this->assertEquals($sVoucherRuleName  ,$oRule->getVoucherRuleName());
        $this->assertEquals($sVoucherPaddingCharacter,$oRule->getVoucherPaddingCharacter());
        $this->assertEquals($sVoucherSuffix ,$oRule->getVoucherSuffix());
        $this->assertEquals($sVoucherPrefix ,$oRule->getVoucherPrefix());
        $this->assertEquals($iVoucherLength ,$oRule->getVoucherLength());
        $this->assertEquals($oDateCreated,$oRule->getDateCreated());
        $this->assertEquals($sSequenceStrategy ,$oRule->getSequenceStrategyName());
        
       
    }
    
    
  
    
    public function testVoucherInstanceProperty()
    {
        $oInstance = new VoucherInstance();
        
        $iVoucherInstanceId = 1;
        $iVoucherTypeId  =  1;
        $sVoucherCode    = '00_111_00';
        $oDateCreated    = new DateTime();
        
        $oInstance->setVoucherInstanceId($iVoucherInstanceId);
        $oInstance->setVoucherTypeId($iVoucherTypeId);
        $oInstance->setVoucherCode($sVoucherCode);
        $oInstance->setDateCreated($oDateCreated);
        
        
        $this->assertEquals($iVoucherInstanceId,$oInstance->getVoucherInstanceId());
        $this->assertEquals($iVoucherTypeId,$oInstance->getVoucherTypeId());
        $this->assertEquals($sVoucherCode,$oInstance->getVoucherCode());
        $this->assertEquals($oDateCreated,$oInstance->getDateCreated());
        
        
    }
    
    public function testVoucherTypeProperty()
    {
        $oVoucher = new VoucherType();
        
        $iVoucherTypeId = 1;
        $sName          ='test voucher';
        $sSlugName      ='test_voucher';
        $sDescription   = 'A sucessful test voucher';
        $oEnableFrom    = new DateTime();
        $oEnableTo      = new DateTime('NOW + 5 days');
        $iVoucherGroupId = 1;
        $iVoucherGenRuleId =1;
        
        $oVoucher->setVoucherTypeId($iVoucherTypeId);
        $oVoucher->setSlug($sSlugName);
        $oVoucher->setName($sName);
        $oVoucher->setDescription($sDescription);
        $oVoucher->setEnabledFrom($oEnableFrom);
        $oVoucher->setEnabledTo($oEnableTo);
        $oVoucher->setVoucherGroupId($iVoucherGroupId);
        $oVoucher->setVoucherGenruleId($iVoucherGenRuleId);
        
        $this->assertEquals($iVoucherTypeId,$oVoucher->getVoucherTypeId());
        $this->assertEquals($sName,$oVoucher->getName());
        $this->assertEquals($sSlugName,$oVoucher->getSlug());
        $this->assertEquals($sDescription,$oVoucher->getDescription());
        $this->assertEquals($oEnableFrom,$oVoucher->getEnabledFrom());
        $this->assertEquals($oEnableTo,$oVoucher->getEnabledTo());
        $this->assertEquals($iVoucherGroupId,$oVoucher->getVoucherGroupID());
        $this->assertEquals($iVoucherGenRuleId,$oVoucher->getVoucherGenRuleId());
        
       
        
    }
    
    
    
}
/* End of File */