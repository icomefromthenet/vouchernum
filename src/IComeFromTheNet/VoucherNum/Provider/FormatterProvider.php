<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use IComeFromTheNet\VoucherNum\Formatter\FormatterBag;

/**
 * Will bootstrap the Sequence Strategy Factory and register default strategies
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class FormatterProvider implements ServiceProviderInterface
{
    
    
    public function getFormatterClass()
    {
        return 'IComeFromTheNet\\VoucherNum\\Formatter\\DefaultFormatter';
    }
     
     
    public function register(Container $pimple)
    {
       
        $sFormatterClass = $this->getFormatterClass();
        
        $pimple['formatterbag'] = function($c) use ($sFormatterClass) {
            return new FormatterBag($sFormatterClass);  
        };
     
    }
    
    
    public function boot(Container $pimple)
    {
        
      
    }
    
}
/* End of Class */