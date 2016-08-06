<?php
namespace IComeFromTheNet\VoucherNum\Test;

use DateTime;
use IComeFromTheNet\VoucherNum\Test\Base\TestWithContainer;
use IComeFromTheNet\VoucherNum\VoucherContainer;
use DBALGateway\Feature\BufferedQueryLogger;
use DBALGateway\Table\GatewayProxyCollection;

abstract class VoucherTestAbstract extends TestWithContainer
{
    
    
  protected function  getGatewayProxyCollection()
  {
      return new GatewayProxyCollection(new \Doctrine\DBAL\Schema\Schema());
  }
    
  /**
   *  Return an instance of the container
   *
   *  @access public
   *  @return IComeFromTheNet\VoucherNum\VoucherContainer
   *
  */
  public function getContainer()
  {
    if(isset($this->oContainer) === false) {
        $this->oContainer = new VoucherContainer($this->getDoctrineConnection(),$this->getEventDispatcher(),$this->getLogger(),$this->getGatewayProxyCollection());
        $this->oContainer->boot($this->getNow());
        
        # register test services
        $this->oContainer['TestQueryLog'] = new BufferedQueryLogger();
        
        $this->oContainer->getEventDispatcher()->addSubscriber($this->oContainer['TestQueryLog']);
      
    }
   
    return $this->oContainer;
  }
       
}
/* End of File */



