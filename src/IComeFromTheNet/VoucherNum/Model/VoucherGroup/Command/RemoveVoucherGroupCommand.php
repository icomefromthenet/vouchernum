<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command;

use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroup;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;

class RemoveVoucherGroupCommand extends VoucherGroup implements ValidationInterface
{
    
    
     
    public function getRules()
    {
        $aRules = parent::getRules();
        
        $aRules['required'] = [['voucherGroupID']];
        
        return $aRules;
        
    }
    
}
/* End of File */
