<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\CreateVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroupGateway;

/**
 * Operation will save a new voucher group, not be used to update existing
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateVoucherGroupHandler 
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
     * @param DateTime          $oNow       The current datetime.
     */ 
    public function __construct(VoucherGroupGateway $oGateway, DateTime $oNow)
    {
        $this->oGateway = $oGateway;
        $this->oNow     = $oNow;
    }
    
    
    
    /**
     * Create a Voucher Group
     * 
     * @param CreateVoucherGroupCommand  $oVoucherGroup  The Voucher Group Entity
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(CreateVoucherGroupCommand $oVoucherGroup)
    {
        $oGateway        = $this->oGateway;
        $oVoucherBuilder = $oGateway->getEntityBuilder();
        $bSuccess        = false;
        
        try {
        
            $oQuery = $oGateway->insertQuery()->start();
            
            foreach($oVoucherBuilder->demolish($oVoucherGroup) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_group_id' : break;
                    case 'date_created' :   
                        $oQuery->addColumn('date_created',$this->oNow);
                    break;
                    default : 
                        $oQuery->addColumn($sColumn,$mValue);
        
                }
                
            }
            
            $bSuccess = $oQuery->end()->insert(); 
            
    
            if($bSuccess) {
                $oVoucherGroup->setVoucherGroupId($oGateway->lastInsertId());
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */