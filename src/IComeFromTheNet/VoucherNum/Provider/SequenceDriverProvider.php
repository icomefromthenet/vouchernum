<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use IComeFromTheNet\VoucherNum\Driver\CommonDriverFactory;

/**
 * Will bootstrap the Sequence database Driver Factory
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class SequenceDriverProvider implements ServiceProviderInterface
{
    
    
    protected function getDriverList()
    {
        return [
            'mysql' => 'IComeFromTheNet\\VoucherNum\\Driver\\MYSQLDriver',
            
        ];
        
    }
    
     
    public function register(Container $pimple)
    {
       
        $pimple['sequence.driver.factory'] = function($c) {
            return  new CommonDriverFactory($c->getDatabaseAdapter(),$c->getEventDispatcher());
        };
     
    }
    
    
    public function boot(Container $pimple)
    {
        
        $oFactory = $pimple->getSequenceDriverFactory();
        
        
        
        foreach($this->getDriverList() as $sName => $sClass) {
            $oFactory->registerDriver($sName,$sClass);    
        }
        
    }
    
}
/* End of Class */