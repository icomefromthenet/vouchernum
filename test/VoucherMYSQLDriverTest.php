<?php
namespace IComeFromTheNet\VoucherNum\Test;

use Doctrine\DBAL\Connection;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;

use IComeFromTheNet\VoucherNum\VoucherException;
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
    
    
    const SEQUENCE_TABLE_NAME = 'ledger_voucher';

    
    public function getDataSet()
    {
      return new ArrayDataSet([__DIR__.'/Fixture/VoucherFixture.php']);
    }

    
    public function testNewDriver()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        
        $this->assertTrue($schema->hasTable(self::SEQUENCE_TABLE_NAME),'Database is missing sequence table::'.self::SEQUENCE_TABLE_NAME);
        
        $driver = new MYSQLDriver($connection,self::SEQUENCE_TABLE_NAME);
        
        $this->assertInstanceOf('IComeFromTheNet\VoucherNum\Driver\SequenceDriverInterface',$driver);    
    }
    
    
    public function testSequenceReturnsValueOnValidSequence()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        $driver = new MYSQLDriver($connection,self::SEQUENCE_TABLE_NAME);
        
        $seq = $driver->sequence('invoice');
        
        $this->assertInternalType('integer',$seq);
        $this->assertGreaterThan(0,$seq,'sequence is not greater than 0');
        
        $seq2 = $driver->sequence('invoice');
        
        $this->assertEquals($seq+1,$seq2);
        
    }
    
    /**
     * @expectedException IComeFromTheNet\Ledger\Exception\LedgerException
     * @expectedExcetpionMessage Unable to update voucher sequence with name aaaa
    */
    public function testSequenceErrorWhenVoucherNotExist()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        $driver = new MYSQLDriver($connection,self::SEQUENCE_TABLE_NAME);
        
        $seq = $driver->sequence('aaaa');
        
    }
    
    
    public function testUUIDReturnsValue()
    {
        $connection = $this->getContainer()->getDatabaseAdapter();
        $schema     = $this->getContainer()->getGatewayFactory()->getSchema();
        $driver = new MYSQLDriver($connection,self::SEQUENCE_TABLE_NAME);
        
        $seq = $driver->uuid('');
        $this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/',$seq);
        $seq2 = $driver->uuid('');
        
        $this->assertNotEquals($seq,$seq2);
    }
    
}
/* End of Class */
