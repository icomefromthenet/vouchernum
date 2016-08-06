<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherType\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ExpireVoucherCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeGateway;

/**
 * Operation will expire an existing voucher type
 * 
 * Set the enabled to date on this entity to the given or default to now
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ExpireVoucherHandler 
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
     * @param ExpireVoucherCommand  $oVoucherType  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(ExpireVoucherCommand $oVoucherType)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess        = false;
        
        try {
        
            $oQuery     = $oGateway->updateQuery()->start();
            $oEnabledTo = null;
            
            foreach($oVoucherBuilder->demolish($oVoucherType) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_enabled_to' :  
                        $oQuery->addColumn($sColumn,$this->oNow); 
                        $oEnabledTo = $this->oNow;
                    break;
                    
                    default:  
                        $oQuery->addColumn($sColumn,$mValue);
                }
                
            }
            
            $bSuccess = $oQuery->where()
                            ->filterByVoucherType($oVoucherType->getVoucherTypeId())
                        ->end()
                        ->update(); 
        
            if($bSuccess) {
                $oVoucherType->setEnabledTo($oEnabledTo);
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */