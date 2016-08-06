<?php
namespace IComeFromTheNet\VoucherNum;

use IComeFromTheNet\VoucherNum\DB\VoucherType;
use IComeFromTheNet\VoucherNum\DB\VoucherGenRule;
use IComeFromTheNet\VoucherNum\DB\VoucherInstance;
use IComeFromTheNet\VoucherNum\DB\VoucherGroup;
use IComeFromTheNet\VoucherNum\VoucherException;

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
    
    
    public function lookupType()
    {
        
        
    }
    
    
    public function lookupGroup()
    {
    
        
    }
    
    
    public function lookupRule()
    {
    
        
    }
    
    
    public function createVoucher(VoucherType $oType, $aResult = array())
    {
        
        $oOperations = $this->getContainer()->getVoucherTypeOperations();
        $oOperation  = $this->getContainer()->getOperationLogDecerator($oOperations['create']);            
        $bSuccess    = false;
    
        $mValid = $oType->validate();
        if(is_array($mValid)) {
           $aResult['error'] = $mValid;
           $aResult['msg']   = 'The new voucher type failed validation test';
        }
        else {
       
            try {
                
                $bSuccess = $oOperation->execute($oType);
                
                if(false === $bSuccess) {
                    $aResult['error'] = null;
                    $aResult['msg']   = 'unknown database issue'; 
                }
                
            } catch(VoucherException $e) {
                $aResult['msg']    = $e->getMessage();
                $aResult['error']  = $e;
            }         
        
        }
        
        $aResult['success'] = $bSuccess;
        
        return $aResult;
    }
    
    public function expireVoucher(VoucherType $oType)
    {
        
    }
    
    
    
    public function reviseVoucher(VoucherType $oType)
    {
        
        
    }
    
    
    public function createGroup(VoucherGroup $oGroup)
    {
    
        
    }
    
    
    public function removeGroup(VoucherGroup $oGroup)
    {
    
        
    }
    
    public function reviseGroup(VoucherGroup $oGroup)
    {
    
        
    }
    
    public function createRule(VoucherGenRule $oRule)
    {
    
        
    }
    
    
    public function reviseRule(VoucherGenRule $oRule)
    {
    
        
    }
    
    
    public function disableRule(VoucherGenRule $oRule)
    {
        
        
    }
    
    public function enableRule(VoucherGenRule $oRule)
    {
        
    }
    
    
    public function generateVoucher(VoucherType $oType)
    {
        
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