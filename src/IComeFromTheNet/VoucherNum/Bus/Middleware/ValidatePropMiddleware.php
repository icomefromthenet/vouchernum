<?php
namespace IComeFromTheNet\VoucherNum\Bus\Middleware;

use Valitron\Validator;
use League\Tactician\Middleware;

use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationException;


/**
 * This middle ware will use the Valitron\Validator to process validation rules
 * applied on the bus command.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ValidatePropMiddleware implements Middleware
{

  
  
    /**
     * Will Validate the command if it implements the valdiation interface
     * 
     * @throws IComeFromTheNet\BookMe\Bus\Middleware\ValidationException
     * @param mixed $oCommand
     * @param callable $next
     * 
     */ 
    public function execute($oCommand, callable $next)
    {
        
        if($oCommand instanceof ValidationInterface) {
          
            $aRules     = $oCommand->getRules();
            $aData      = $oCommand->getData();
            $oValidator = new Validator($aData);
            
        
            $oValidator->rules($aRules);
        
            $bValid = $oValidator->validate();
        
            if(false === $bValid) {
                throw ValidationException::hasFailedValidation($oCommand,$oValidator->errors());
            }
        
        }
        
        
        $returnValue = $next($oCommand);
        
        return $returnValue;
    }
  
  
  
}
/* End of Clas */