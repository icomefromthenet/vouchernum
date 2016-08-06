<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherType\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeGateway;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\NewVoucherTypeCommand;

/**
 * Operation will save a new voucher type, not be used to update existing
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class NewVoucherTypeHandler
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
     * @param NewVoucherTypeCommand  $oVoucherType  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(NewVoucherTypeCommand $oVoucherType)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess        = false;
        
      
        try {
        
            $oQuery = $oGateway->insertQuery()->start();
            $oEnabledToDate = date_create_from_format('Y-m-d','3000-01-01');
            
            foreach($oVoucherBuilder->demolish($oVoucherType) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_type_id': 
                    break; 
                    case 'voucher_enabled_to': 
                        $oQuery->addColumn('voucher_enabled_to',$oEnabledToDate); 
                    break;
                    default :   
                        $oQuery->addColumn($sColumn,$mValue);
                }
                
            }
            
            $bSuccess = $oQuery->end()->insert(); 
            
            if($bSuccess) {
                $oVoucherType->setVoucherTypeId($oGateway->lastInsertId());
                $oVoucherType->setEnabledTo($oEnabledToDate);
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */