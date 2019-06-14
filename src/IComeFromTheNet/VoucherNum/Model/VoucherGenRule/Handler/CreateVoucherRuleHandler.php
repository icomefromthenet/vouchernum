<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\CreateVoucherRuleCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRuleGateway;

/**
 * Operation will save a new voucher rule, not be used to update existing
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CreateVoucherRuleHandler 
{
    
    /**
     * @var VoucherGenRuleGateway
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
     * @param VoucherGenRuleGateway    $oGateway   The Database Table Gateway
     * @param DateTime                 $oNow       The current datetime.
     */ 
    public function __construct(VoucherGenRuleGateway $oGateway, DateTime $oNow)
    {
        $this->oGateway = $oGateway;
        $this->oNow     = $oNow;
    }
    
    
    
    /**
     * Create a Voucher Rule
     * 
     * @param CreateVoucherRuleCommand  $oVoucherRule  The Voucher Gen Rule
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(CreateVoucherRuleCommand $oVoucherRule)
    {
        $oGateway        = $this->oGateway;
        $oBuilder        = $oGateway->getEntityBuilder();
       
     
        try {
        
            $oQuery = $oGateway->insertQuery()->start();
            
            foreach($oBuilder->demolish($oVoucherRule) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_gen_rule_id' : break;
                    case 'date_created' : 
                        $oQuery->addColumn('date_created',$this->oNow);
                    break;
                    default :  
                        $oQuery->addColumn($sColumn,$mValue);
                }
                
            }
            
            // set a starting value for the sequence
            $oQuery->addColumn('voucher_sequence_no',0);
            
            $bSuccess = $oQuery->end()->insert(); 
    
            
    
            if($bSuccess) {
                $oVoucherRule->setVoucherGenRuleId($oGateway->lastInsertId());
            }
        
        }
        catch(DBALGatewayException $e) {
            throw new VoucherException($e->getMessage(),0,$e);
        }
        
        
        return $bSuccess;    
    }
    
}
/* End of Class */