<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use IComeFromTheNet\VoucherNum\Rule\ValidationRuleRegistry;
use IComeFromTheNet\VoucherNum\Rule\AlwaysInvalidRule;
use IComeFromTheNet\VoucherNum\Rule\AlwaysValidRule;

/**
 * Will bootstrap the Validation Rules Registry
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ValidRuleProvider implements ServiceProviderInterface
{
    
    
    protected function getValidationRulesMap()
    {
        return [
             'always-valid'   => 'valid.rule.always'
            ,'always-invalid' => 'valid.rule.never'
        ];
        
    }
     
     
     
    public function register(Container $pimple)
    {
       
       $aRuleMap = $this->getValidationRulesMap();
       

       $pimple['valid.rule.registry'] = function($c) use ($aRuleMap) {
           return new ValidationRuleRegistry($c,$aRuleMap);
       };
       
       
       
       $pimple['valid.rule.always'] = function($c) {
            return new  AlwaysValidRule(); 
       };
       
       
       $pimple['valid.rule.never'] = function($c) {
            return new AlwaysInvalidRule();
       };
       
   
     
    }
    
    
    public function boot(Container $pimple)
    {
       
    }
    
}
/* End of Class */