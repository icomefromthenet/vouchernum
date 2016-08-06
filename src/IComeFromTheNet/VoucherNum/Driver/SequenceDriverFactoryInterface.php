<?php
namespace IComeFromTheNet\VoucherNum\Driver;

use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
  *  Implements Factory Behaviour to load database platform
  *  drivers for each sequence factory.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
interface SequenceDriverFactoryInterface
{
    
    
    /**
     *  Register a driver instance class
     *  The driver will be lazy loaded
     *
     *  @access public
     *  @return SequenceDriverFactoryInterface
     *  @param string $platform same as SequenceDriverInterface::getPlatform();
     *  @param string $class a fully qualified name of calss
     *
    */
    public function registerDriver($platform,$class);
    
    
    /**
     *  Doctrine DBAL Connection
     *  A dependency of driver classes
     *
     *  @access public
     *  @return Doctrine\DBAL\Connection
     *
    */
    public function getDBAL();
    
    
    /**
     *  Load the Event Dispatcher
     *
     *  @access public
     *  @return  Symfony\Component\EventDispatcher\EventDispatcherInterface;
     *
    */ 
    public function getEventDispatcher();
    
    /**
     *  Create and instance of a driver when given the platform
     *
     *  @access public
     *  @return SequenceDriverInterface
     *  @param string $platform same as SequenceDriverInterface::getPlatform();
     *  $param string $table the database table for vouchers
    */
    public function getInstance($platform,$table);
    
}
/* End of Interface */
