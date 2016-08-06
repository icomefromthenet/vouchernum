<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup;

use DBALGateway\Query\AbstractQuery;
use DateTime;
use IComeFromTheNet\Voucher\VoucherContainer;

/**
 * Builds Voucher Group Queries 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */
class VoucherGroupQuery extends AbstractQuery
{
    
    
    /**
     *  Filter a voucher group by its Primary Key
     * 
     * @param integer $id   The Voucher PK
     * @return VoucherGroupQuery
     */ 
    public function filterByGroup($id)
    {
        $oGateway = $this->getGateway();
        $sAlias   = $this->getDefaultAlias();
        if(false === empty($sAlias)) {
            $sAlias = $sAlias .'.';
        }
        
        $paramType = $oGateway->getMetaData()->getColumn('voucher_group_id')->getType();
        
        return $this->andWhere($sAlias."voucher_group_id = ".$this->createNamedParameter($id,$paramType));
        
    }
   

}
/* End of Class */

