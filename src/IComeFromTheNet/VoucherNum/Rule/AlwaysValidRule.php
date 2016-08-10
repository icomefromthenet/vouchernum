<?php
namespace IComeFromTheNet\VoucherNum\Rule;


/**
  *  A Rule that will return valid to a check.
  *
  *  Used in testing.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class AlwaysValidRule implements ValidationRuleInterface
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
        return true;
    }
    
    
    /**
     *  Return the validation rules name.
     *
     *  Referend to by voucherType::SetSlugRule()
     *  Referend to by voucherType::getSlugRule()
     *
     *  @access public
     *  @return void
     *
    */
    public function getName()
    {
        return 'always-valid';
    }
    
    
}
/* End of Class */
