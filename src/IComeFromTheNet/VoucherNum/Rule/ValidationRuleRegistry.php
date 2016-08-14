<?php
namespace IComeFromTheNet\VoucherNum\Rule;

use Pimple\Container;
use IComeFromTheNet\VoucherNum\VoucherException;

/**
  *  Implements Registry for validation rules
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class ValidationRuleRegistry
{
    
    /**
     * @var Pimple\Container
     */ 
    protected $oContainer;
    
    /**
     * @var a map of rule names to references in the container.
     */ 
    protected $aRulesMap;
    
    
    
    public function __construct(Container $oContainer, array $aRulesMap)
    {
        $this->oContainer = $oContainer;
        $this->aRulesMap  = $aRulesMap;
    }
    
    
    
    /**
     *  Create and instance of a rule
     *
     *  @access public
     *  @return IComeFromTheNet\VoucherNum\Rule\ValidationRuleInterface
     *  @param string $name     SequenceStrategyInterface::getStrategyName()
    */
    public function getInstance($sName)
    {
        if(false === isset($this->aRulesMap[$sName])) {
            throw new VoucherException("The validation rule $sName does not exists in map");
        }
        
        return $this->oContainer->offsetGet($this->aRulesMap[$sName]);
    }
    
    
    
    
}
/* end of class */
