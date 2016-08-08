<?php
namespace IComeFromTheNet\VoucherNum\Driver;

use Doctrine\DBAL\Connection;
use IComeFromTheNet\VoucherNum\VoucherException;

/**
  *  Driver for the mysql plaform. 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class MYSQLDriver implements SequenceDriverInterface
{
    
    
    const PLATFORM = 'mysql';
    
    /**
     *  @var Doctrine\DBAL\Connection the database connection
    */
    protected $dbal;
    
    /*
     * @var string the sequence table name
     */
    protected $sequenceTableName;
    
    
    /**
     *  Class Constructor
     *
     *  @access public
     *  @return void
     *
    */
    public function __construct(Connection $dbal,$sequenceTableName)
    {
        $this->dbal               = $dbal;
        
        if(empty($sequenceTableName)) {
           throw new VoucherException(sprinf("The sequence table name is empty string"));
        }
        
        $this->sequenceTableName = $sequenceTableName;
    }
    
    
   //-------------------------------------------------------
   # SequenceDriverInterface
    
    public function getPlatform()
    {
        return self::PLATFORM;
    }
    
    
    //-------------------------------------------------------
    # SequenceInterface
    
    /*
     * Generate a uniqe incrementing number
     *
     * @access public
     * @return integer|string a sequence value
     * @param string $sequenceName the sequence name
     *
     */
    public function sequence($name)
    {
        # will find the current voucher ie where max-date is set
        $updateStr = 'UPDATE '.$this->sequenceTableName. ' SET voucher_sequence_no = LAST_INSERT_ID(voucher_sequence_no + 1) WHERE voucher_rule_slug = ?;';
        $selectStr = 'SELECT LAST_INSERT_ID();';
        
        # update row
        if($this->dbal->executeUpdate($updateStr,array($name)) == 0) {
            throw new VoucherException('Unable to update voucher sequence with name '.$name);
        }
        
        # select the return
        $statement =  $this->dbal->prepare($selectStr);
        
        if($statement->execute() === false) {
            throw new VoucherException('Unable to reterive last updated sequence for name '.$name);
        }
        
        return (integer) $statement->fetchColumn(0);
    }
    
 
    
}
/* End of Class */
