<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\RemoveVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroupGateway;

/**
 * Operation will remove a voucher groug.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class RemoveVoucherGroupHandler
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
     * Remove a Voucher Group
     * 
     * @param RemoveVoucherGroupCommand  $oVoucherGroup  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(RemoveVoucherGroupCommand $oVoucherGroup)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess        = false;
      
        try {
            // Note: the FK will stop voucher groups from being removed if they are used. 
            $bSuccess = $oGateway->deleteQuery()
                                ->start()
                                    ->filterByGroup($oVoucherGroup->getVoucherGroupId())
                                ->end()
                            ->delete(); 
            
            if(!$bSuccess) {
                throw new VoucherException('Unable to remove voucher at id::' .$oVoucherGroup->getVoucherGroupID());
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */