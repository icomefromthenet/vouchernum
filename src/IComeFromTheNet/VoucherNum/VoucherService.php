<?php
namespace IComeFromTheNet\VoucherNum;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;

/**
 * Voucher
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class VoucherService
{
    
    /**
     * @var VoucherContainer
     */ 
    protected $container;
    
    /**
     * @var the errors from last validation check
     */ 
    protected $aLastValidationResults;
    
    
    /**
     * Return a new voucher number
     * 
     */ 
    protected function generateVoucher(VoucherType $oVoucherType)
    {
        $aRules    = [];
        $oSequence = null;
        
        // Lookup the rule that apply
        
        $oVoucherRule = $this->getContainer()
                             ->getGateway(VoucherContainer::DB_TABLE_VOUCHER_RULE)
                             ->selectQuery()
                                ->start()
                                    ->filterByRule($oVoucherType->getVoucherGenRuleId())
                                ->end()
                             ->findOne();
        
        if(true === empty($oVoucherRule)) {
            throw new VoucherException('Unable to find voucher Generator Rule');
        }
        
        
        //Lookup any voucher validation rules
        
        foreach($oVoucherRule->getValidationRules() as $sRuleName) {
            $aRules[] = $this->getContainer()->getValidationRuleRegistry()->getInstance($sRuleName);
        }
        
        
        // Build the Sequence Strategy
        $oSequence = $this->getContainer();
        
        
        // Instance the Generator
        
        
        
    }
    
    
    /**
     * Voucher Service Constructor
     * 
     * @param VoucherContainer $container this modules service container
     * 
     */ 
    public function __construct(VoucherContainer $container)
    {
        $this->container = $container;
        
    }
    
    
    //--------------------------------------------------------------------------
    # Public API
    
    
    public function generateVoucherByName($sVoucherTypeSlugName)
    {
        $oVoucherType = $this->getContainer()
                             ->getGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE)
                             ->selectQuery()
                                ->start()
                                   ->filterByVoucherTypeName($sVoucherTypeSlugName)
                                ->end()
                             ->findOne(); 
        
        if(true === empty($oVoucherType)) {
            throw new VoucherException('Unable to find voucher with name :: '.$sVoucherTypeSlugName);
        }
        
        return $this->generateVoucher($oVoucherType);
        
    }
    
    
    public function generateVoucherById($iVoucherTypeDatabaseId)
    {
        $oVoucherType = $this->getContainer()
                             ->getGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE)
                                ->selectQuery()
                                    ->start()
                                        ->filterByVoucherType($iVoucherTypeDatabaseId)
                                    ->end()
                              ->findOne(); 
        
        if(true === empty($oVoucherType)) {
            throw new VoucherException('Unable to find voucher at id :: '.$iVoucherTypeDatabaseId);
        }
        
        return $this->generateVoucher($oVoucherType);
    }
    
    //--------------------------------------------------------------------------
    # Properties
    
    /**
     * Return the modules service container
     * 
     * @return VoucherContainer
     */ 
    public function getContainer()
    {
       return $this->container; 
    }
  
}
/* End of File /*