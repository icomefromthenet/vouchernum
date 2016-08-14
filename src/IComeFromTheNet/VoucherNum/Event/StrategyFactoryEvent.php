<?php
namespace IComeFromTheNet\VoucherNum\Event;

use Exception;
use Symfony\Component\EventDispatcher\Event;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\Strategy\StrategyFactoryInterface;


/**
  *  Event object for events
  *
  *  VoucherEvents::SEQUENCE_STRATEGY_REGISTERED
  *  VoucherEvents::SEQUNENCE_STRATEGY_INSTANCED
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class StrategyFactoryEvent extends Event
{
    protected $factory;
    
    
    protected $strategyName;
    
    protected $className;
   
    public function __construct(StrategyFactoryInterface $factory, $name, $className)
    {
        $this->factory      = $factory;
        $this->strategyName = $name;
        $this->className    = $className;
        
    }
    
    /**
     *  Returns the strategy factory that raised the event
     *
     *  @access public
     *  @return IComeFromTheNet\VoucherNum\Strategy\StrategyFactoryInterface
     *
    */
    public function getFactory()
    {
        return $this->factory;        
    }
    
    /**
     *  Returns the strategy name
     *
     *  @access public
     *  @return string
     *
    */
    public function getSrategyName()
    {
        return $this->strategyName;
    }
   
    
    /**
     *  Fetch the strategy class name
     *
     *  @access public
     *  @return string the class name
     *
    */
    public function getClassName()
    {
        return $this->className;
    }

}
/* End of Class */



