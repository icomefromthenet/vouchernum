<?php
namespace IComeFromTheNet\VoucherNum\Test;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Formatter\FormatBagInterface;
use IComeFromTheNet\VoucherNum\Formatter\FormatterBag;
use IComeFromTheNet\VoucherNum\Formatter\FormatterInterface;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;

/**
  *  Test the Voucher Validation Rule Bag
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherFormatterBagTest extends VoucherTestAbstract
{
    
    
    public function getDataSet()
    {
      return new ArrayDataSet([]);
    }

    
    public function testAddFormatter()
    {
        $bag = new FormatterBag();
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $bag->addFormatter('test',$formatter);
    }
    
    /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedEXceptionMessage test already been added to the formatter Bag
     *
    */
    public function testErrorAddSameRuleTwice()
    {
        $bag = new FormatterBag();
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $bag->addFormatter('test',$formatter);
        $bag->addFormatter('test',$formatter);
        
    }
    
    
    public function testRemoveRule()
    {
        $bag = new FormatterBag();
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $bag->addFormatter('test',$formatter);
        
        $this->assertTrue($bag->removeFormatter('test'));
        $bag->addFormatter('test',$formatter); #'no error'
    }
    
    
    public function testIteratreOverBag()
    {
        $bag = new FormatterBag();
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $this->assertInstanceOf('IteratorAggregate',$bag);
        $this->assertInstanceOf('Iterator',$bag->getIterator());
    }
    
}
/* End of Class */
