<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGenRule;

use DBALGateway\Table\AbstractTable;
use DBALGateway\Table\SchemaAwareTable;
use IComeFromTheNet\VoucherNum\Model\CommonGateway;

/**
 * Gateway to the voucher generator rules database table
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class VoucherGenRuleGateway extends CommonGateway
{
    
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return VoucherGenRuleQuery
      */
    public function newQueryBuilder()
    {
        return new VoucherGenRuleQuery($this->adapter,$this);
    }

}
/* End of Class */
