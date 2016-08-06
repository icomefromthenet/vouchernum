<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGenRule;

use DateTime;
use Valitron\Validator;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;


/**
 * A Entity for the Voucher Generator Rule
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class VoucherGenRule implements ValidationInterface
{
    
    /**
     * @var integer the voucher rule database id
     */ 
    protected $iVoucherGeneratorRuleId;
    
    /**
     * @var string a slug version of voucher name
     */ 
    protected $sVoucherRuleNameSlug;
    
    /**
     * @var string the human name for this rule
     */ 
    protected $sVoucherRuleName;
    
    /**
     * @var character to use to pad a voucher number to min length 
     */
    protected $sVoucherPaddingCharacter;
    
    /**
     * @var a suffix to include in the voucher number
     */ 
    protected $sVoucherSuffix;
    
    /**
     * @var a prefix to include in the voucher number
     */ 
    protected $sVoucherPrefix;
    
    /**
     * @var integer the length of a voucher number
     */ 
    protected $iVoucherLength;
    
    /**
     * @var the date this rule was created
     */ 
    protected $oDateCreated;
    
    /**
     * @var string the method used to generate the unqiue part of voucher name
     */ 
    protected $sSequenceStrategy;
    
    /**
     * Fetches the database rule id
     * 
     * @return integer the database id
     * @access public
     */ 
    public function getVoucherGenRuleId()
    {
        return $this->iVoucherGeneratorRuleId;
    }
    
    /**
     * Sets the database id of this rule
     * 
     * @access public
     * @return void
     * @param   integer $iRuleId    The Database id
     */ 
    public function setVoucherGenRuleId($iRuleId)
    {
        $this->iVoucherGeneratorRuleId = (int) $iRuleId;
    }
    
    /**
     * Fetch the slugified rule name
     * 
     * @access public
     * @return string the slug version of the name
     */ 
    public function getSlugRuleName()
    {
        return $this->sVoucherRuleNameSlug;
    }
    
   /**
    * Sets the slug version of this rules name
    * 
    * @access public
    * @return void
    * @param string $sSlug  A slug version of rule name
    */
    public function setSlugRuleName($sSlug)
    {
        $this->sVoucherRuleNameSlug = (string) $sSlug;
    }
    
    /**
     * Fetch the human rule name
     * 
     * @return string the human rule name
     * @access public
     */ 
    public function getVoucherRuleName()
    {
        return $this->sVoucherRuleName;
    }
    
    /**
     * Sets the human rule name
     * 
     * @access public
     * @return void
     * @param string $sRuleName The human voucher rule name
     */ 
    public function setVoucherRuleName($sRuleName)
    {
        $this->sVoucherRuleName = (string) $sRuleName;
    }
    
    
    /**
     * Return the padding character
     * 
     * @return string the padding character
     * @access public
     */ 
    public function getVoucherPaddingCharacter()
    {
        return $this->sVoucherPaddingCharacter;
    }
    
    /**
     * Sets a voucher number padding character
     * 
     * @access public
     * @return void
     * @param string    $sPaddingChar   The sequence padding character
     */ 
    public function setVoucherPaddingCharacter($sPaddingChar)
    {
        $this->sVoucherPaddingCharacter = (string) $sPaddingChar;
    }
    
     /**
     * Gets a suffix to attach to voucher number
     * 
     * @access public
     * @return string  A suffix to attach to voucher number
     */ 
    public function getVoucherSuffix()
    {
        return $this->sVoucherSuffix;
    }
    
    /**
     * Sets a suffix to attach to voucher number
     * 
     * @return void
     * @access public
     * @param string    $sSuffix    A suffix to attach to voucher number
     */ 
    public function setVoucherSuffix($sSufix)
    {
        $this->sVoucherSuffix = $sSufix;
    }
    
    /**
     * Gets a prefix to attach to voucher number
     * 
     * @access public
     * @return string  A prefix to attach to voucher number
     */ 
    public function getVoucherPrefix()
    {
        return $this->sVoucherPrefix;
    }
    
    /**
     * Sets a prefix to attach to voucher number
     * 
     * @return void
     * @access public
     * @param string    $sPrefix    A prefix to attach to voucher number
     */ 
    public function setVoucherPrefix($sPrefix)
    {
        $this->sVoucherPrefix = (string) $sPrefix;
    }
    
    /**
     * Gets the assigned length of voucher number
     * 
     * This number of characters in unique number not max possible number
     * 
     * @access public
     * @return integer the voucher number length
     */ 
    public function getVoucherLength()
    {
        return $this->iVoucherLength;
    }
    
    /**
     * Sets the voucher numbers length 
     * 
     * This number of characters in unique number not max possible number
     * 
     * @param integer   $iLength    The voucher number length
     * @access public
     * @return void
     */
    public function setVoucherLength($iLength)
    {
        $this->iVoucherLength = (int) $iLength;
    }
    
    /**
     * Fetches the date this rule was added to db
     * 
     * @access public
     * @return DateTime when rule was added to db
     */ 
    public function getDateCreated()
    {
        return $this->oDateCreated;
    }
    
    /**
     * Sets the created date of this rule (assigned by db)
     * 
     * @param DateTime  $oDateCreated   The create date
     * @access public
     * @return void
     */
    public function setDateCreated(DateTime $oDateCreated)
    {
        $this->oDateCreated = $oDateCreated;
    }
    
     /**
     * Set the identifier of the sequence method to generate unique part
     * of a voucher code
     * 
     * @return string the name of the strategy to use
     * @access public
     */ 
    public function getSequenceStrategyName()
    {
       return $this->sSequenceStrategy;
    }
   
    /**
     * Set the identifier of the sequence method to generate unique part
     * of a voucher code
     * 
     * @return void
     * @param string    $sName  The name of the strategy to use
     * @access public
     */  
    public function setSequenceStrategyName($sName)
    {
       $this->sSequenceStrategy = (string) $sName;
    }
    
    //------------------------------------------------------
    
    public function getTotalLength()
    {
         // ensure total length < 255 column size for the voucher code
        // generated with the current db schema and size of padding,suffix
        $iTotalLength  = (int)$this->getVoucherLength();
        $iTotalLength += mb_strlen((string)$this->getVoucherSuffix());
        $iTotalLength += mb_strlen((string)$this->getVoucherPrefix());
        
        return $iTotalLength;
        
    }
    
    
    //--------------------------------------------------------
    # Validation Interface
    
    public function getData()
    {
        return [
           'voucherGenRuleID'  => $this->getVoucherGenRuleId()
          ,'slugName'   => $this->getSlugRuleName()
          ,'voucherRuleName' => $this->getVoucherRuleName()
          ,'voucherPaddingChar' => $this->getVoucherPaddingCharacter()
          ,'voucherSuffix' =>  $this->getVoucherSuffix()
          ,'voucherPrefix'   => $this->getVoucherPrefix()
          ,'voucherLength'   => $this->getVoucherLength()
          ,'sequenceStrategy' => $this->getSequenceStrategyName()
          ,'totalLength'       => $this->getTotalLength(),
    
        ];
        
    }
    
    
 
    public function getRules()
    {
        return [
            'slug' => [
                ['slugName']
                
            ]
            ,'length' => [
                ['voucherPaddingChar',1]
                
            ]
            ,'lengthBetween' => [
                ['slugName',1,25], ['name',1,25], ['voucherSuffix',0,50], ['voucherPrefix',0,50], ['totalLength',1,255]
                
            ]
            ,'required' => [
                ['slugName'], ['voucherRuleName'], ['voucherLength']
                
            ]
            ,'min' => [
                ['voucherGenRuleID',1], ['voucherLength',1]
                
            ]
            ,'max' => [
                ['voucherLength',100]
                
            ]
            ,'in' => [
               ['sequenceStrategy',['SEQUENCE']] 
                
            ]
            
        ];
        
       
    } 
    
    
}
/* End of class */
