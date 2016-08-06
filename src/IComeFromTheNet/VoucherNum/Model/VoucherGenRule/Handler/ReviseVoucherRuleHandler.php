<?php 
namespace IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Handler;

use DateTime;
use DBALGateway\Exception as DBALGatewayException;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\ReviseVoucherRuleCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRuleGateway;

/**
 * Operation will save a existing voucher rule
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class ReviseVoucherRuleHandler
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
     * Update a Voucher Rule
     * 
     * @param ReviseVoucherRuleCommand  $oVoucherRule  The Voucher Gen Rule
     * @throws VoucherException if the database query fails or entity has id assigned.
     * @returns boolean true if the insert operation was successful
     */ 
    public function handle(ReviseVoucherRuleCommand $oVoucherRule)
    {
        $oGateway        = $this->oGateway;
        $oBuilder        = $oGateway->getEntityBuilder();
       
        try {
        
            $oQuery = $oGateway->updateQuery()->start();
            
            foreach($oBuilder->demolish($oVoucherRule) as $sColumn => $mValue) {
                switch($sColumn) {
                    case 'voucher_gen_rule_id':
                    case 'date_created':
                    break;
                    default: 
                        $oQuery->addColumn($sColumn,$mValue);
                }

            }
            
            $bSuccess = $oQuery->where()
                    ->filterByRule($oVoucherRule->getVoucherGenRuleId())
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