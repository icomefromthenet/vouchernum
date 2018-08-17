<?php
namespace IComeFromTheNet\VoucherNum\Test;

use Doctrine\DBAL\Connection;
use IComeFromTheNet\VoucherNum\Test\Base\ArrayDataSet;

use IComeFromTheNet\VoucherNum\Driver\CommonDriverFactory;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;
use IComeFromTheNet\VoucherNum\VoucherException;


/**
  *  Test the Voucher Driver Factory
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherDriverFactoryTest extends VoucherTestAbstract
{
    
    
    const SEQUENCE_TABLE_NAME = 'ledger_voucher';
    
    public function getDataSet()
    {
       return new ArrayDataSet([]);
    }
    
    
    public function testRegister()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $event      = $this->getContainer()->getEventDispatcher();
    
        $factory = new CommonDriverFactory($connection,$event);
        $factory->registerDriver('psql','IComeFromTheNet\VoucherNum\Driver\MYSQLDriver');
        
        $this->assertTrue(true);
    }
    
    /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExceptionMessage Platform psql already registered with factory 
    */
    public function testExceptionRegisterExistingDriver()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $event      = $this->getContainer()->getEventDispatcher();
    
        $factory = new CommonDriverFactory($connection,$event);
        
        $factory->registerDriver('psql','IComeFromTheNet\VoucherNum\Driver\MYSQLDriver');
        $factory->registerDriver('psql','IComeFromTheNet\VoucherNum\Driver\MYSQLDriver');
    }
    
    public function testNewDriver()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $event      = $this->getContainer()->getEventDispatcher();
    
        $factory = new CommonDriverFactory($connection,$event);
        
        $factory->registerDriver('mysql','IComeFromTheNet\VoucherNum\Driver\MYSQLDriver');
        
        $this->assertInstanceOf('IComeFromTheNet\VoucherNum\Driver\MYSQLDriver',$factory->getInstance('mysql',self::SEQUENCE_TABLE_NAME));
    }
    
}
/* End of Class */
