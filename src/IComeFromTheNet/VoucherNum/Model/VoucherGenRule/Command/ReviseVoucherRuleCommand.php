<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRule;

class ReviseVoucherRuleCommand extends VoucherGenRule implements ValidationInterface
{
    
    
    public function getRules()
    {
        $aRules = parent::getRules();
        
        $aRules['required'][] = ['voucherGenRuleID'];
        
        return $aRules;
    }
    
    
}
/* End of File */
