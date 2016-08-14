<?php
namespace IComeFromTheNet\VoucherNum\Formatter;

use ArrayIterator;
use Zend\Stdlib\StringWrapper\MbString;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRule;

/**
  *  A Bag to contain instanced formatters, Possible for a rule to share many voucher type, this
  *  allows formatters to be resused.
  *
  *  A formatter combines the sequence with a prefix and suffix
  *  with optional length and any other params defined at runtime
  *  to produce a voucher reference
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class FormatterBag implements FormatterBagInterface
{
    
    protected $formatters = [];
    
    protected $sFormatterClass = null;
    
    
    
    
    public function __construct($sFormatterClass) 
    {
        $this->sFormatterClass = $sFormatterClass;
    }
    
    
    /**
     *  Adds a formatter if not set
     *
     *  @access public
     *  @return void
     *  @param string $name the reference name
     *  @param FormatBagInterface $f the instanced formatter
     *
    */
    public function addFormatter($name,FormatterInterface $f)
    {
        if(isset($this->formatters[$name])) {
            throw new VoucherException("$name already been added to the Formatter Bag");
        }
        
        $this->formatters[$name] = $f;
    }
    
    /**
     *  Return a formatter at name
     *
     *  @access public
     *  @return FormatterInterface|null the assigned formatter
     *  @param string $name
    */
    public function getFormatter($name)
    {
        $formatter = null;
        if(isset($this->formatters[$name])) {
            $rule = $this->formatters[$name];
        }
        
        return $$formatter;
    }
    
    /**
     *  Remove an assigned formatter
     *
     *  @access public
     *  @return boolean true if removed
     *  @param string $name
     *
    */
    public function removeFormatter($name)
    {
        $removed = false;
        
        if($this->existsFormatter($name)) {
            unset($this->formatters[$name]);
            $removed = true;
        }

        return $removed;
    }
    
    /**
     *  Check the formatter has been added
     *
     *  @access public
     *  @return boolean true if exists internally
     *  @param string $name
     *
    */
    public function existsFormatter($name)
    {
        return isset($this->formatters[$name]);
    }
    
    
    
    //-------------------------------------------------------
    # IteratorAggregate Interface
    
    
    public function getIterator()
    {
        return new \ArrayIterator($this->formatters);
    }
    
    
    //-------------------------------------------------------------
    
    /**
     * Instance a formatter with the given rule, if the formatter
     * alrady e
     * 
     */ 
    public function addFormatterForRule(VoucherGenRule $oRule) 
    {
        // does rule ready loaded
        $iRuleId = $oRule->getVoucherGenRuleId();
        $oFormatter = null;
        
        if(true === $this->existsFormatter($iRuleId)) {
            $oFormatter = $this->getFormatter($iRuleId);
        }
        else {
            
            $sClass = $this->sFormatterClass;
            
            $oFormatter = new $sClass(
                 new MbString()
                ,$oRule->getVoucherSuffix()
                ,$oRule->getVoucherPrefix()
                ,$oRule->getVoucherLength()
                ,$oRule->getVoucherPaddingCharacter()
            );
            
        }
        
        
        return $oFormatter;
    }
}
/* End of Class */
 