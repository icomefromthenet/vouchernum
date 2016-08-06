<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherType\Command;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;

class ExpireVoucherTypeCommand extends VoucherType implements ValidationInterface
{
    
    
    public function getRules()
    {
        $aRules = parent::getRules();
        
        $aRules['required'][] = ['voucherTypeId'];
        
        return $aRules;
    }
    
    
}
/* End of File */
