<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherInstance\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\VoucherInstance;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\VoucherInstanceGateway;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\Command\CreateVoucherCommand;

/**
 * Operation will save a new voucher instance, not be used to update existing
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateVoucherHandler 
{
    
    /**
     * @var VoucherInstanceGateway
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
     * @param VoucherInstanceGateway    $oGateway   The Database Table Gateway
     * @param DateTime                  $oNow       The current datetime.
     */ 
    public function __construct(VoucherInstanceGateway $oGateway, DateTime $oNow)
    {
        $this->oGateway = $oGateway;
        $this->oNow     = $oNow;
    }
    
    
    
    /**
     * Create a Voucher Instance
     * 
     * @param VoucherInstance  $oVoucherGroup  The Voucher Instance to save
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(CreateVoucherCommand $oVoucherInstance)
    {
        $oGateway        = $this->oGateway;
        $oBuilder        = $oGateway->getEntityBuilder();
       
        try {
        
            $oQuery = $oGateway->insertQuery()->start();
            
            foreach($oBuilder->demolish($oVoucherInstance) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_instance_id': break;
                    case 'date_created' :       
                            $oQuery->addColumn('date_created',$this->oNow); 
                    break;
                    default :       
                            $oQuery->addColumn($sColumn,$mValue);    
                }
                
            }
            
            $bSuccess = $oQuery->end()->insert(); 
    
    
            if($bSuccess) {
                $oVoucherInstance->setVoucherInstanceId($oGateway->lastInsertId());
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */