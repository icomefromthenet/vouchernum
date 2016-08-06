<?php
namespace IComeFromTheNet\VoucherNum\Rule;

use IComeFromTheNet\VoucherNum\ValidationRuleInterface;

/**
  *  A Rule that will return invalid to a check.
  *
  *  Used in testing.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class MaxLengthRule implements ValidationRuleInterface
{
    /**
     *  Validate a voucher reference
     *
     *  @access public
     *  @return boolean true if valid
     *  @param string $voucherReference the reference to validate
     *
    */
    public function validate($voucherReference)
    {
        return false;
    }
    
    /**
     *  Return the validation rules name.
     *
     *  @access public
     *  @return void
    */
    public function getName()
    {
        return 'max-length';
    }
    
    
}
/* End of Class */
