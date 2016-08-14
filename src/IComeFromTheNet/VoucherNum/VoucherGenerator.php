<?php
namespace IComeFromTheNet\VoucherNum;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\Command\CreateVoucherCommand;

/**
 * Voucher Generator, once loaded with a VoucherType will setup the sequence
 * ready to produce.
 *
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class VoucherGenerator
{
    
    /**
     * @var VoucherContainer
     */ 
    protected $container;
    
    /**
     * @var the errors from last validation check
     */ 
    protected $oVoucherType;
    
    /**
     * @var IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface
     */ 
    protected $oVoucherSequence;
    
    /**
     * @var IComeFromTheNet\VoucherNum\Formatter\FormatterInterface
     */ 
    protected $oFormatter;
    
    /**
     * @var IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRule
     */ 
    protected $oVoucherRule;
    
    
    protected $aValidationRules =  [];
    
    /**
     * @var mixed the last generated voucher 
     */ 
    protected $mLastResult;
    
    /**
     * Return a new voucher number
     * 
     */ 
    protected function loadVoucher(VoucherType $oVoucherType)
    {
        $aRules    = [];
        $oSequence = null;
        
        $sPlatform = $this->getContainer()
                          ->getDatabaseAdapter()
                          ->getDatabasePlatform()
                          ->getName();
        
        
        $this->oVoucherType = $oVoucherType;
        
        
       
       
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
        
        $this->oVoucherRule = $oVoucherRule;
        
        
        
        //Lookup any voucher validation rules
        
        foreach($oVoucherRule->getValidationRules() as $sRuleName) {
            $aRules[] = $this->getContainer()->getValidationRuleRegistry()->getInstance($sRuleName);
        }
        
        $this->aValidationRules = $aRules;
        
        
        
        // Build the Sequence Strategy, this throw an exception is method not exist.
        $oSequence = $this->getContainer()->getSequenceFactory()->getInstance($oVoucherRule->getSequenceStrategyName(),$sPlatform, VoucherContainer::DB_TABLE_VOUCHER_RULE);
        
        $this->oVoucherSequence = $oSequence;
        
        
        // Instance the Formatter
        $oFormatter= $this->getContainer()->getFormatterBag()->addFormatterForRule($oVoucherRule);
        
        $this->oFormatter = $oFormatter;
        
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
    
    
    public function setVoucherByName($sVoucherTypeSlugName)
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
        
        
        $this->loadVoucher($oVoucherType);
        
        return $this;
        
    }
    
    
    public function setVoucherById($iVoucherTypeDatabaseId)
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
        
        $this->loadVoucher($oVoucherType);
      
        return $this;
    }
    
    
    
    
    public function generate($iOptionalSequenceValue = null)
    {
        if($this->oVoucherType === null) {
            throw new VoucherException('A Voucher Type must me loaded first');
        }
        
        $this->mLastResult = '';
        
        // Fetch Next Sequence if we supplied one then don't need to use sequence emulator
        if($iOptionalSequenceValue === null) {
           $iOptionalSequenceValue = $this->oVoucherSequence->nextVal($this->oVoucherRule->getSlugRuleName());
        }
        
        // Generate number
        $this->mLastResult = $this->oFormatter->format($iOptionalSequenceValue);
        
        // run through validation this throw and exception if voucher is not valid
        foreach($this->aValidationRules as $oRule) {
            $oRule->validate($oRule);
        }
        
        // Insert into history table this ensure the voucher is unique
        $oCommand = new CreateVoucherCommand();
        $oCommand->setVoucherTypeId($this->oVoucherType->getVoucherTypeId());
        $oCommand->setVoucherCode($this->mLastResult);
        
        $this->getContainer()->getCommandBus()->handle($oCommand);
        
        
        
        return $this->mLastResult;
    }
    
    public function lastResult()
    {
        return $this->mLastResult;
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
/* End of File */