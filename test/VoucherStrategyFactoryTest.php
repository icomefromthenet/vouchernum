<?php
namespace IComeFromTheNet\VoucherNum\Test;

use Doctrine\DBAL\Connection;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use IComeFromTheNet\VoucherNum\Strategy\SequenceStrategyInterface;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface;
use IComeFromTheNet\VoucherNum\Strategy\StrategyFactoryInterface;
use IComeFromTheNet\VoucherNum\SequenceInterface;
use IComeFromTheNet\VoucherNum\Event\VoucherEvents;
use IComeFromTheNet\VoucherNum\Event\StrategyFactoryEvent;
use IComeFromTheNet\VoucherNum\Strategy\CommonStrategyFactory;
use IComeFromTheNet\VoucherNum\Strategy\AutoIncrementStrategy;

/**
  *  Test the Voucher UUIDStrategyFactory
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherStrategyFactoryTest extends VoucherTestAbstract
{
   
   public function getDataSet()
   {
      return new ArrayDataSet([]);
   }
 
   public function testNewFactory()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new  CommonStrategyFactory($driver,$event);
    
      $this->assertInstanceOf('IComeFromTheNet\\VoucherNum\\Strategy\\StrategyFactoryInterface',$strategy);
   }
 
   public function testRegisterNew()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
    
      $event->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(VoucherEvents::SEQUENCE_STRATEGY_REGISTERED),$this->isInstanceOf('IComeFromTheNet\VoucherNum\Event\StrategyFactoryEvent'));
     
      $strategy = new  CommonStrategyFactory($driver,$event);
    
      $strategy->registerStrategy('aaa','IComeFromTheNet\\VoucherNum\\Strategy\\AutoIncrementStrategy');
    
    
   }
 
    /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExcetpionMessage Sequence strategy sequence already registered with factory
    */
   public function testRegisterErrorWhenAlreadySet()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new  CommonStrategyFactory($driver,$event);
    
      $strategy->registerStrategy('sequence','IComeFromTheNet\\VoucherNum\\Strategy\\AutoIncrementStrategy');
    
   }
   
   /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExcetpionMessage Sequence strategy aaa does not exist
    */
   public function testRegisterErrorWhenNotExist()
   {
      $driver = $this->createMock('IComeFromTheNet\VoucherNum\Driver\SequenceDriverFactoryInterface');
      $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
     
      $strategy = new  CommonStrategyFactory($driver,$event);
    
      $strategy->registerStrategy('aaa','IComeFromTheNet\\VoucherNum\\Strategy\\AutoIncrementStrategysss');
    
   }
   
}