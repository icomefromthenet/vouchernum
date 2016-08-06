<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherInstance\Command;

use IComeFromTheNet\VoucherNum\Model\VoucherInstance;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;

/**
 * Create A Voucher Instance in the database.
 * 
 * The table has a unique index if a duplicate is inserted it will fail.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateVoucherCommand extends VoucherInstance implements ValidationInterface
{
    
  
     
    
}
/* End of File */

