<?php

namespace IComeFromTheNet\VoucherNum\Test;

use IComeFromTheNet\VoucherNum\Test\Base\TestWithContainer;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IComeFromTheNet\Ledger\Exception\LedgerException;
use IComeFromTheNet\VoucherNum\Strategy\UUIDStrategy;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\SequenceInterface;
use IComeFromTheNet\VoucherNum\Event\VoucherEvents;
use IComeFromTheNet\VoucherNum\Event\SequenceEvent;

/**
  *  Test the Voucher UUIDStrategy
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherUUIDStrategyTest extends TestWithContainer
{
 
   public function testNewStrategy()
   {
      $driver = $this->getMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new  UUIDStrategy($driver,$event);
    
      $this->assertInstanceOf('IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface',$strategy);
      $this->assertInstanceOf('IComeFromTheNet\VoucherNum\SequenceInterface',$strategy);
   }
 
 
   public function testProperties()
   {
      $driver = $this->getMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new UUIDStrategy($driver,$event);
      
      $this->assertEquals($driver,$strategy->getDriver());
      $this->assertEquals($event,$strategy->getEventDispatcher());
      $this->assertEquals('sequence',$strategy->getStrategyName());
    
   }
   
   
   public function testNextVal()
   {
    
      $driver = $this->getMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
      $seqName = 'invoice';
     
      $driver->expects($this->once())
             ->method('uuid')
             ->with($this->equalTo($seqName))
             ->will($this->returnValue(100));
     
      $event->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_BEFORE),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
      
      $event->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_AFTER),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
     
      $strategy = new UUIDStrategy($driver,$event);
      
      $this->assertEquals(100,$strategy->nextVal($seqName));
    
   }
   
   /**
     * @expectedException IComeFromTheNet\Ledger\Exception\LedgerException
     * @expectedExcetpionMessage Unable to update voucher sequence with name invoice
    */
   public function testEsceptionNextValDriverThrowsException()
   {
    
      $driver = $this->getMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
      $seqName = 'invoice';
     
      $driver->expects($this->once())
             ->method('uuid')
             ->with($this->equalTo($seqName))
             ->will($this->throwException(new LedgerException('Unable to update voucher sequence with name invoice')));
     
      $event->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_BEFORE),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
      
      $strategy = new UUIDStrategy($driver,$event);
      
      $this->assertEquals(100,$strategy->nextVal($seqName));
    
   }
   
   
}