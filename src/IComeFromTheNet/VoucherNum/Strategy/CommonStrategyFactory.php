<?php
namespace IComeFromTheNet\VoucherNum\Strategy;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Strategy\StrategyFactoryInterface;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface;
use IComeFromTheNet\VoucherNum\Event\VoucherEvents;
use IComeFromTheNet\VoucherNum\Event\StrategyFactoryEvent;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\Strategy\AutoIncrementStrategy;


/**
  *  Factory that builds Sequence Strategies
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class CommonStrategyFactory implements StrategyFactoryInterface
{
    
    protected $strategyInstances = array();
    
    protected $eventDispatcher;
    
    protected $driverFactory;
    
    
    /**
     *  Class Constructor
     *
     *  @access public
     *  @return void
     *  @param SequenceDriverFactoryInterface $driverFactory
     *  @param EventDispatcherInterface $dispatcher
     *
    */
    public function __construct(SequenceDriverFactoryInterface $driverFactory, EventDispatcherInterface $dispatcher)
    {
        $this->driverFactory     = $driverFactory;
        $this->eventDispatcher   = $dispatcher;
        $this->strategyInstances = array();
       
    }
    
    
     /**
     *  Register a driver instance class
     *  The driver will be lazy loaded
     *
     *  @access public
     *  @return StrategyFactoryInterface
     *  @param string $name  SequenceStrategyInterface::getStrategyName() 
     *  @param string $class a fully qualified name of class
     *
    */
    public function registerStrategy($name,$class)
    {
        if(isset($this->strategyInstances[$name])) {
            throw new VoucherException("Sequence strategy $name already registered with factory");
        }
        
        if(class_exists($class) === false) {
            throw new VoucherException("Sequence strategy $class does not exist");
        }
        
        $this->strategyInstances[$name] = $class;
        
        $this->getEventDispatcher()->dispatch(VoucherEvents::SEQUENCE_STRATEGY_REGISTERED,new StrategyFactoryEvent($this,$name,$class));
        
        return $this;
    }
    
    
    /**
     *  Factory that loads database drivers
     *
     *  @access public
     *  @return IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface
     *
    */
    public function getDriverFactory()
    {
        return $this->driverFactory;
    }
    
    
    /**
     *  Load the Event Dispatcher
     *
     *  @access public
     *  @return  Symfony\Component\EventDispatcher\EventDispatcherInterface;
     *
    */ 
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
   
    
    
    /**
     *  Create and instance of a strategy
     *
     *  @access public
     *  @return SequenceStrategyInterface
     *  @param string $name     SequenceStrategyInterface::getStrategyName()
     *  @param string $platform SequenceDriverInterface::getPlatform()
    */
    public function getInstance($name,$platform)
    {
        if(isset($this->strategyInstances[$name]) === true) {
            throw new VoucherException("Sequence strategy $name not registered with factory");
        }
        
        if(!$class instanceof SequenceStrategyInterface ) {
            
            $class = $this->strategyInstances[$name];
            $this->factoryInstances[$name] = new $class($this->getDriverFactory()->getInstance($platform),$this->getEventDispatcher());
            
            $this->getEventDispatcher()->dispatch(VoucherEvents::SEQUNENCE_STRATEGY_INSTANCED,
                                             new StrategyFactoryEvent($this,
                                                                      $name,
                                                                      $class,
                                                                      $this->factoryInstances[$platform]
                                                                    )
                                            );
        }
        
        return $this->factoryInstances[$name];
    }
    
}
/* End of Class */
