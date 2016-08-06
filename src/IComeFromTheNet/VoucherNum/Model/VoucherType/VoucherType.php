<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherType;


use DateTime;
use Exception;
use Valitron\Validator;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;

/**
  *  Represent a custom Ledger Entry type.
  *
  *  Ledger transaction are often divied into groups, for example
  *
  *  1. General Journals
  *  2. Sales Recepits
  *  3. Invoices
  *  4. etc
  *
  *  As we can't know every voucher type this module allows
  *  developers to define their own types.
  *
  *  Each voucher is identified by a 'voucher reference' / 'voucher code'
  *
  *  {prefix}sequence{suffix}
  *
  *  e.g GL_503
  *
  *  This entity support temporal validity they have same name and slug 
  *  but will have a different date pair.
  *
  *  You can change the prefix for sales recepits and set that to start at date X
  *
  *  A voucher slug name is used as the Foreign Key column.
  *  The ledger will only load the valid entities as of the given processing date and
  *  for each name there can be only one valid entity at a time.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class VoucherType 
{
    
    protected $iVoucherTypeId;
    protected $sName;
    protected $sDescription;
    protected $oEnableFrom;
    protected $oEnableTo;
    protected $sSlugName;
    protected $iVoucherGroupId;
    protected $iVoucherGenRuleId;
    
        
    
    
    /**
     * Fetch the database if of this type entity
     * 
     * @return integer  The database id
     */ 
    public function getVoucherTypeId()
    {
        return $this->iVoucherTypeId;
    }
    
    /**
     * Sets the database id for this entity
     * 
     * @access public
     * @param integer   $iTypeId    The database id
     * @return void
     */ 
    public function setVoucherTypeId($iTypeId)
    {
        $this->iVoucherTypeId = (int) $iTypeId;
    }
    
    
    /**
     *  Gets the voucher slug name (database_id)
     *
     *  @access public
     *  @return string the slug
     *
    */
    public function getSlug()
    {
       return $this->sSlugName;
    }
    
    /**
     *  Set the voucher slug name (database id)
     *
     *  @access public
     *  @return void
     *  @param string $sSlug the database identity
     *
    */
    public function setSlug($sSlug)
    {
    
        $this->sSlugName = (string) $sSlug;
    }
    
    /**
     *  Return this voucher types name
     *
     *  @access public
     *  @return string
     *
    */
    public function getName()
    {
        return $this->sName;
    }
    
    /**
     *  Set a name for this voucher type, must not be empty
     *
     *  @access public
     *  @return void
     *  @param string $sName max 100 characters
     *
    */
    public function setName($sName)
    {
        $this->sName = (string) $sName;
    }
    
    /**
     *  Sets the description of this voucher type
     *
     *  @access public
     *  @return void
     *
    */
    public function getDescription()
    {
        return $this->sDescription; 
    }
    
    /**
     *  Sets a description for this voucher type
     *
     *  @access public
     *  @return void
     *  @param string $sDescription max 500 characters
     *
    */
    public function setDescription($sDescription)
    {
        $this->sDescription = (string) $sDescription;
    }
    
    
    /**
     *  Get the date this voucher Type will be available from
     *
     *  @access public
     *  @return DateTime
     *
    */
    public function getEnabledFrom()
    {
        return $this->oEnableFrom;
    }
    
    /**
     *  Set date this voucher Type will be available from
     *
     *  @access public
     *  @return void
     *  @param DateTime $oFrom
     *
    */
    public function setEnabledFrom(DateTime $oFrom)
    {
        $this->oEnableFrom = $oFrom;
    }
    
    
    /**
     *  Gets the date this voucher type will be unavailable.
     *
     *  @access public
     *  @return DateTime
     *
    */
    public function getEnabledTo()
    {
        return $this->oEnableTo;
    }
    
    /**
     *  Sets the date this voucher type will be unavailable.
     *
     *  i.e. soft delete.
     *
     *  @access public
     *  @return void
     *  @param DateTime $oTo 
     *
    */    
    public function setEnabledTo(DateTime $oTo = null)
    {
        $this->oEnableTo = $oTo;
    }
    
    
   
    
    /**
     * Fetch the voucher group id that this type belongs too
     * 
     * @return integer The group database if
     * @access public
     */ 
    public function getVoucherGroupId()
    {
        return  $this->iVoucherGroupId;
    }
    
    /**
     * 
     * Sets the voucher group id that this type belongs too
     * 
     * @param integer    $iGroupId   The group database if
     * @access public
     * @return void
     */ 
    public function setVoucherGroupId($iGroupId)
    {
        $this->iVoucherGroupId = (int) $iGroupId;   
    }
    
    /**
     * Gets the generator rule that build the code
     * 
     * @return integer  The database id of the assigned rule
     * @access public 
     */ 
    public function getVoucherGenRuleId()
    {
       return $this->iVoucherGenRuleId;
    }
    
    /**
     * Sets the generator rule that will build the voucher code
     * 
     * @param integer   $iRuleId    The database if of the rule
     * @return void
     * @access public
     */ 
    public function setVoucherGenruleId($iRuleId)
    {
       $this->iVoucherGenRuleId = (int) $iRuleId;
    }
    
    
    //--------------------------------------------------------------------------
    # Validation Interface
    
    
    public function getData()
    {
        return [
            'voucherTypeId'      => $this->getVoucherTypeId(),
            'voucherGenRuleId'   => $this->getVoucherGenRuleID(),
            'voucherGroupId'     => $this->getVoucherGroupId(),
            'voucherEnabledFrom' => $this->getEnabledFrom(),
            'voucherEnabledTo'   => $this->getEnabledTo(),
            'voucherDescription' => $this->getDescription(),
            'voucherName'        => $this->getName(),
            'voucherNameSlug'    => $this->getSlug()
            
        ];
    }
    
    

    public function getRules()
    {
        return [
            'lengthBetween' => [
                ['voucherNameSlug',1,100],['voucherName',1,100],['voucherDescription',1,500]
            ]
            ,'slug' => [
                ['voucherNameSlug']
            ]
            ,'required' => [
                ['voucherName'],['voucherGenRuleId'],['voucherName'],['voucherGroupId']
            ]
            ,'instanceOf' => [
                ['voucherEnabledFrom','DateTime'],['voucherEnabledTo','DateTime']
            ]
            ,'min' => [
               ['voucherTypeId',1],['voucherGroupId',1],['voucherGenRuleId',1]
            ]
            ,'max' => [
               ['voucherTypeId',4294967295],['voucherGroupId',4294967295],['voucherGenRuleId',4294967295]
            ]
        
        ];

    }
    
    
    
    
 
}
/* End of Class */
