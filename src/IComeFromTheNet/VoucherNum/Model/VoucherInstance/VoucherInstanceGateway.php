<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherInstance;

use DBALGateway\Table\AbstractTable;
use DBALGateway\Table\SchemaAwareTable;
use IComeFromTheNet\VoucherNum\Model\CommonGateway;

/**
 * Gateway to the voucher group instance table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class VoucherInstanceGateway extends CommonGateway
{
    
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return VoucherGroupQuery
      */
    public function newQueryBuilder()
    {
        return new VoucherInstanceQuery($this->adapter,$this);
    }

}
/* End of Class */
