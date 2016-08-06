<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherType;

use DBALGateway\Table\AbstractTable;
use IComeFromTheNet\VoucherNum\Model\CommonGateway;

/**
  *  Voucher Type TableGateway
  *
  *  Map entity to database
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherTypeGateway extends CommonGateway
{
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return IComeFromTheNet\Ledger\Query\AccountGroupQuery
      */
    public function newQueryBuilder()
    {
        return new VoucherTypeQuery($this->adapter,$this);
    }
    
    
    
}
/* End of Class */