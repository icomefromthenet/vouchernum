<?php
namespace IComeFromTheNet\VoucherNum\Test;

use IComeFromTheNet\VoucherNum\Test\Base\TestWithContainer;
use IComeFromTheNet\VoucherNum\Rule\AlwaysInvalidRule;
use IComeFromTheNet\VoucherNum\Rule\AlwaysValidRule;
use IComeFromTheNet\VoucherNum\ValidationRuleBag;

/**
  *  Test the Voucher Validation Rule Bag
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherRuleBagTest extends TestWithContainer
{
    
    
    public function testAddRule()
    {
        $bag = new ValidationRuleBag();
        $rule = new AlwaysInvalidRule();
        
        $this->assertSame($bag,$bag->addRule($rule));
    }
    
    /**
     * @expectedException IComeFromTheNet\Ledger\Exception\LedgerException
     * @expectedEXceptionMessage $voucherSlug already been added to the Rule Bag
     *
    */
    public function testErrorAddSameRuleTwice()
    {
        $bag = new ValidationRuleBag();
        $rule = new AlwaysInvalidRule();
        
        $bag->addRule($rule);
        $bag->addRule($rule);
    }
    
    
    public function testRemoveRule()
    {
        $bag = new ValidationRuleBag();
        $rule = new AlwaysInvalidRule();
        
        $bag->addRule($rule);
        $this->assertSame($bag,$bag->removeRule('always-invalid'));
        $bag->addRule($rule); # add without exception
    }
    
    
    public function testIteratreOverBag()
    {
        $bag = new ValidationRuleBag();
        $this->assertInstanceOf('IteratorAggregate',$bag);
        $this->assertInstanceOf('Iterator',$bag->getIterator());
    }
    
}
/* End of Class */
