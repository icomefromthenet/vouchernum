<?php
namespace IComeFromTheNet\VoucherNum\Event;

use Exception;
use Symfony\Component\EventDispatcher\Event;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;

/**
  *  Event object for events
  *
  *  VoucherEvents::SEQUNENCE_BEFORE
  *  VoucherEvents::SEQUENCE_AFTER
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class SequenceEvent extends Event
{
    
    protected $strategy;
    
    protected $driver;
    
    protected $value;
    
    
    
    public function __construct($strategy, $driver, $value = null)
    {
        $this->strategy = $strategy;
        $this->driver   = $driver;
        $this->value    = $value;
    }
    
    
    /**
     *  Fetch the sequence strategy
     *
     *  @access public
     *  @return IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface
     *
    */
    public function getStrategy()
    {
        return $this->strategy;        
    }
    
    
    /**
     *  Fetch the database driver
     *
     *  @access public
     *  @return IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface
     *
    */
    public function getDriver()
    {
        return $this->driver;
    }
    
    /**
     *  Gets the Sequence Value
     *
     *  @access public
     *  @return mixed the sequence
     *
    */
    public function getValue()
    {
        return $this->value;
    }

}
/* End of Class */