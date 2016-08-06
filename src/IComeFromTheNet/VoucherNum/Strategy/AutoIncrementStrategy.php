<?php
namespace IComeFromTheNet\VoucherNum\Strategy;

use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IComeFromTheNet\VoucherNum\Event\VoucherEvents;
use IComeFromTheNet\VoucherNum\Event\SequenceEvent;

/**
  *  Uses the database to generate a unique identity.
  *
  *  Will use a combination of named sequences and
  *  autoincrement columns. Driver will implement each
  *  solution on a given plaform
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class AutoIncrementStrategy implements SequenceStrategyInterface
{
    
    const STRATEGY_NAME = 'sequence';
    
    
    /**
     * @var IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface
    */
    protected $driver;
    
    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
    */
    protected $event;
    
    /**
     *  Class constructor
     *
     *  @access public
     *  @return void
     *  @param SequenceDriverInterface $driver the database driver
     *  @param EventDispatcherInterface $dispatcher the event dispatcher
     *
    */
    public function __construct(SequenceDriverInterface $driver, EventDispatcherInterface $dispatcher)
    {
        $this->driver = $driver;
        $this->event  = $dispatcher;
    }
    
    
    /**
     *  Fetch the event dispatcher 
     *
     *  @access public
     *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface
     *
    */
    public function getEventDispatcher()
    {
        return $this->event;
    }
   
    
    /*
     * @inheritDoc
     */
    public function getDriver()
    {
        return $this->driver;     
        
    }
    
   
    /*
     * @inheritDoc
     */
    public function getStrategyName()
    {
        return self::STRATEGY_NAME;
    }
    
    
    /*
     * Generate an incrementing value
     *
     * @access public
     * @return integer|string a sequence value
     * @param string $sequenceName the sequence name
     *
     */
    public function nextVal($sequenceName)
    {
        $this->getEventDispatcher()->dispatch(VoucherEvents::SEQUENCE_BEFORE, new SequenceEvent($this,$this->getDriver()));
            $seq =  $this->getDriver()->sequence($sequenceName);
        $this->getEventDispatcher()->dispatch(VoucherEvents::SEQUENCE_AFTER,new SequenceEvent($this,$this->getDriver(),$seq));
        
        return $seq;
    }
}
/* End of Class */
