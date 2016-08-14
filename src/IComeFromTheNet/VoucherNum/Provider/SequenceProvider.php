<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use IComeFromTheNet\VoucherNum\Strategy\CommonStrategyFactory;

/**
 * Will bootstrap the Sequence Strategy Factory and register default strategies
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class SequenceProvider implements ServiceProviderInterface
{
    
    
    protected function getSequenceStrategyList()
    {
        return [
           'sequence' =>  'IComeFromTheNet\\VoucherNum\\Strategy\\AutoIncrementStrategy'
            
            
        ];
        
    }
     
     
    public function register(Container $pimple)
    {
       
        $pimple['sequence.factory'] = function($c) {
            return  new CommonStrategyFactory($c->getSequenceDriverFactory(),$c->getEventDispatcher());
        };
     
    }
    
    
    public function boot(Container $pimple)
    {
        
        $oFactory = $pimple->getSequenceFactory();
        
        foreach($this->getSequenceStrategyList() as $sName => $sClass) {
            $oFactory->registerStrategy($sName,$sClass);
        }
    }
    
}
/* End of Class */