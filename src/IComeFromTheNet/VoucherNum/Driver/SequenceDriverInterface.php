<?php
namespace IComeFromTheNet\VoucherNum\Driver;

use IComeFromTheNet\VoucherNum\SequenceInterface;

/**
  *  A class that generate a sequence on a given platform
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
interface SequenceDriverInterface 
{
    
    /**
     *  SQL Vendor Platform
     *
     *  oracle|mysql|pgsql|mssql|sqlite|dbase ... etc
     *
     *  @access public
     *  @return string the sql vendor platform abbr
     *
    */
    public function getPlatform();
    
    
    
    /**
     * Release a lock on table row
     * 
     * @return boolean true if lock released
     * @param string the name of the row to lock
     */ 
    public function unlockRow($name);
    
    
    
}
/* End of Interface */
