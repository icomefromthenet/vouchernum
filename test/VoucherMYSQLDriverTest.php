<?php
namespace IComeFromTheNet\VoucherNum\Test;

use Doctrine\DBAL\Connection;
use IComeFromTheNet\VoucherNum\Test\Base\ArrayDataSet;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\VoucherContainer;
use IComeFromTheNet\VoucherNum\Driver\MYSQLDriver;
use IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface;


/**
  *  Test the Voucher MYSQL Sequence Driver
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherMYSQLDriverTest extends VoucherTestAbstract
{
    
    
    
    
    public function getDataSet()
    {
      return new ArrayDataSet([__DIR__.'/Fixture/VoucherFixture.php']);
    }

    
    public function testNewDriver()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        
        $driver = new MYSQLDriver($connection,VoucherContainer::DB_TABLE_VOUCHER_RULE);
        
        $this->assertInstanceOf('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface',$driver);    
    }
    
    
    public function testSequenceReturnsValueOnValidSequence()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        $driver = new MYSQLDriver($connection,VoucherContainer::DB_TABLE_VOUCHER_RULE);
        
        $seq = $driver->sequence('rule_fixture_a');
        
        $this->assertInternalType('integer',$seq);
        $this->assertGreaterThan(0,$seq,'sequence is not greater than 0');
        
        $seq2 = $driver->sequence('rule_fixture_a');
        
        $this->assertEquals($seq+1,$seq2);
        
    }
    
    /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExcetpionMessage Unable to update voucher sequence with name aaaa
    */
    public function testSequenceErrorWhenVoucherNotExist()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        $driver = new MYSQLDriver($connection,VoucherContainer::DB_TABLE_VOUCHER_RULE);
        
        $seq = $driver->sequence('aaaa');
        
    }
    
    
}
/* End of Class */
