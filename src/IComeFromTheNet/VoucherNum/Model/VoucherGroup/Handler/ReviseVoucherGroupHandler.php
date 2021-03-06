<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\ReviseVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroupGateway;

/**
 * Operation will save an existing voucher group
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ReviseVoucherGroupHandler 
{
    
    /**
     * @var VoucherGateway
     */ 
    protected $oGateway;
    
    /**
     * @var DateTime
     */ 
    protected $oNow;
    
    
    /**
     * Class Constructor
     * 
     * @access public
     * @return void
     * @param VoucherGroupGateway    $oGateway   The Database Table Gateway
     * @param DateTime               $oNow       The current datetime.
     */ 
    public function __construct(VoucherGroupGateway $oGateway, DateTime $oNow)
    {
        $this->oGateway = $oGateway;
        $this->oNow     = $oNow;
    }
    
    
    
    /**
     * Create a Voucher Group
     * 
     * @param ReviseVoucherGroupCommand  $oVoucherGroup  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(ReviseVoucherGroupCommand $oVoucherGroup)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess         = false;
       
       
        try {
        
            $oQuery = $oGateway->updateQuery()->start();
            
            foreach($oVoucherBuilder->demolish($oVoucherGroup) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_group_id' : 
                    case 'date_created' : 
                    break;
                    default: $oQuery->addColumn($sColumn,$mValue);
                        
                }
                
            }
            
            $bSuccess = $oQuery->where()
                                    ->filterByGroup($oVoucherGroup->getVoucherGroupId())
                                ->end()
                            ->update(); 
    
    
            if($bSuccess) {
                $oVoucherGroup->setVoucherGroupID($oGateway->lastInsertId());
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
       
        
        return $bSuccess;    
    }
    
}
/* End of Class */