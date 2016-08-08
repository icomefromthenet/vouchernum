<?php
namespace IComeFromTheNet\VoucherNum\Test;

use Doctrine\DBAL\Connection;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Strategy\AutoIncrementStrategy;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\SequenceInterface;
use IComeFromTheNet\VoucherNum\Event\VoucherEvents;
use IComeFromTheNet\VoucherNum\Event\SequenceEvent;


/**
  *  Test the Voucher AutoIncrementStrategy
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherAutoIncrementStrategyTest extends VoucherTestAbstract
{
 
   
    
   public function getDataSet()
   {
      return new ArrayDataSet([]);
   }
    
 
   public function testNewStrategy()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new AutoIncrementStrategy($driver,$event);
    
      $this->assertInstanceOf('IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface',$strategy);
      $this->assertInstanceOf('IComeFromTheNet\VoucherNum\SequenceInterface',$strategy);
   }
 
 
   public function testProperties()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new AutoIncrementStrategy($driver,$event);
      
      $this->assertEquals($driver,$strategy->getDriver());
      $this->assertEquals($event,$strategy->getEventDispatcher());
      $this->assertEquals('sequence',$strategy->getStrategyName());
    
   }
   
   
   public function testNextVal()
   {
    
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
      $seqName = 'invoice';
     
      $driver->expects($this->once())
             ->method('sequence')
             ->with($this->equalTo($seqName))
             ->will($this->returnValue(100));
     
      $event->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_BEFORE),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
      
      $event->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_AFTER),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
     
      $strategy = new AutoIncrementStrategy($driver,$event);
      
      $this->assertEquals(100,$strategy->nextVal($seqName));
    
   }
   
   /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExcetpionMessage Unable to update voucher sequence with name invoice
    */
   public function testEsceptionNextValDriverThrowsException()
   {
    
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
      $seqName = 'invoice';
     
      $driver->expects($this->once())
             ->method('sequence')
             ->with($this->equalTo($seqName))
             ->will($this->throwException(new VoucherException('Unable to update voucher sequence with name invoice')));
     
      $event->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_BEFORE),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\SequenceEvent'));
      
      $strategy = new AutoIncrementStrategy($driver,$event);
      
      $this->assertEquals(100,$strategy->nextVal($seqName));
    
   }
   
   
}