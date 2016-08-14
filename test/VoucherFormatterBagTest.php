<?php
namespace IComeFromTheNet\VoucherNum\Test;

use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Formatter\FormatBagInterface;
use IComeFromTheNet\VoucherNum\Formatter\FormatterBag;
use IComeFromTheNet\VoucherNum\Formatter\FormatterInterface;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRule;
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
        $bag = new FormatterBag('IComeFromTheNet\\VoucherNum\\Formatter\\DefaultFormatter');
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
        $bag = new FormatterBag('IComeFromTheNet\\VoucherNum\\Formatter\\DefaultFormatter');
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $bag->addFormatter('test',$formatter);
        $bag->addFormatter('test',$formatter);
        
    }
    
    
    public function testRemoveRule()
    {
        $bag = new FormatterBag('IComeFromTheNet\\VoucherNum\\Formatter\\DefaultFormatter');
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $bag->addFormatter('test',$formatter);
        
        $this->assertTrue($bag->removeFormatter('test'));
        $bag->addFormatter('test',$formatter); #'no error'
    }
    
    
    public function testIteratreOverBag()
    {
        $bag = new FormatterBag('IComeFromTheNet\\VoucherNum\\Formatter\\DefaultFormatter');
        $formatter = $this->createMock('IComeFromTheNet\VoucherNum\Formatter\FormatterInterface');
        
        $this->assertInstanceOf('IteratorAggregate',$bag);
        $this->assertInstanceOf('Iterator',$bag->getIterator());
    }
    
    
    public function testAddFormatterFromRule()
    {
        $oVoucherRule = new VoucherGenRule();
        
        $oVoucherRule->setVoucherGenRuleId(1);
        $oVoucherRule->setVoucherRuleName('Rule One');
        $oVoucherRule->setSlugRuleName('rule_one');
        $oVoucherRule->getVoucherPaddingCharacter();
        $oVoucherRule->getVoucherPrefix();
        $oVoucherRule->getVoucherSuffix();
        $oVoucherRule->getVoucherLength();
        $oVoucherRule->getDateCreated();
        $oVoucherRule->getSequenceStrategyName('S');
        $oVoucherRule->setValidationRules(array('always-valid'));
        
    }
}
/* End of Class */
