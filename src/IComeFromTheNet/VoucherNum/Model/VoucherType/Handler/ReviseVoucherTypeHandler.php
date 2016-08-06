<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherType\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ReviseVoucherTypeCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeGateway;

/**
 * Operation will save an existing voucher type
 * 
 * Will allow only changes to non temporal columns
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ReviseVoucherTypeHandler
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
     * @param VoucherTypeGateway    $oGateway   The Database Table Gateway
     * @param DateTime              $oNow       The current datetime.
     */ 
    public function __construct(VoucherTypeGateway $oGateway, DateTime $oNow)
    {
        $this->oGateway = $oGateway;
        $this->oNow     = $oNow;
    }
    
    
    
    /**
     * Create a Voucher Type
     * 
     * @param ReviseVoucherTypeCommand  $oVoucherType  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(ReviseVoucherTypeCommand $oVoucherType)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess        = false;
        
        try {
        
            $oQuery = $oGateway->updateQuery()->start();
            
            foreach($oVoucherBuilder->demolish($oVoucherType) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_type_id' : 
                    case 'voucher_enabled_to' : 
                    case 'voucher_enabled_from' : 
                    break;
                    default: $oQuery->addColumn($sColumn,$mValue);
                }
               
            }
            
            $bSuccess = $oQuery->where()
                            ->filterByVoucherType($oVoucherType->getVoucherTypeId())
                        ->end()
                        ->update(); 
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */