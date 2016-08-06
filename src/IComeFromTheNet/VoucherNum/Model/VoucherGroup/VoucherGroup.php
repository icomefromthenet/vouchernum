<?php
namespace IComeFromTheNet\VoucherNum\Model\VoucherGroup;

use \DateTime;
use Valitron\Validator;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidationInterface;


class VoucherGroup implements ValidationInterface
{
    
    protected $voucherID;
    protected $name;
    protected $dteCreated;
    protected $sortOrder;
    protected $isDisabled;
    protected $slugName;
    
    /**
     * Fetch the vouchers group database id
     * 
     * @return integer the database id
     */ 
    public function getVoucherGroupId()
    {
        return $this->voucherID;
    }
    
    /**
     * Set the Group Database ID
     * 
     * @param   integer $id The database id
     * @return  void
     */ 
    public function setVoucherGroupId($id)
    {
        $this->voucherID = (integer) $id;
    }
    
    /**
     * Is this group disabled
     * 
     * @return boolean true if disabled
     */ 
    public function getDisabledStatus()
    {
       return $this->isDisabled; 
    }
    
    /**
     * Sets the disabled status of this group
     * 
     * @param   boolean $isDisabled The disabled status of this group
     * @return  void
     */ 
    public function setDisabledStatus($isDisabled)
    {
        $this->isDisabled =  (boolean) $isDisabled;
    }
    
    /**
     * Returns the human name of this group
     * 
     * @return   string the human name of group
     */ 
    public function getVoucherGroupName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name of the group
     * 
     * @param   string  $name   the human name
     * @return  void
     */ 
    public function setVoucherGroupName($name)
    {
        $this->name = (string) $name;
    }
    
    /**
     * Returns a slug version of group name
     * 
     * @return string slug name
     * 
     */ 
    public function getSlugName()
    {
        return $this->slugName;
    }
    
    /**
     * Sets the slug version of the name
     * 
     * @access public
     * @param string    $slugName   The slug version of group name
     * @return void
     */ 
    public function setSlugName($slugName)
    {
        $this->slugName = (string) $slugName;
    }
    
    
    /**
     * Get the sort order for this group
     * 
     * @return integer the sort order
     */ 
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
    
    /**
     * Sets the sort order for this group
     * 
     * @param integer   $iOrder A value to sort this group within a list
     */ 
    public function setSortOrder($iOrder)
    {
        $this->sortOrder = (integer) $iOrder;
    }
    
    
    /**
     * Fetches the assigned time this group was created
     * The value is assigned by the database
     * 
     * @return DateTime the creation date
     */ 
    public function getDateCreated()
    {
        return $this->dteCreated;
    }
    
    /**
     * Sets the assigned time this group was created
     * The value is assigned by the database
     * 
     * @param   DateTime  $created  The creation date assigne by database
     */ 
    public function setDateCreated(DateTime $created)
    {
        return $this->dteCreated = $created;
    }
    
    //--------------------------------------------------------
    # Validation Interface
    
    
    public function getData()
    {
        return [
           'voucherGroupID' => $this->getVoucherGroupId()
          ,'name'           => $this->getVoucherGroupName()
          ,'sortOrder'      => $this->getSortOrder()
          ,'isDisabled'     => $this->getDisabledStatus()
          ,'slugName'       => $this->getSlugName()
        ];
        
    }
    
    public function getRules()
    {
        return [
            'slug' => [
                ['slugName']
            ]
            ,'lengthBetween' => [
                ['slugName',1,100], ['name',1,100]
            ]
            ,'required' => [
                ['slugName'],['name'],['isDisabled']
            ]
            ,'min' => [
                ['voucherGroupID',1]
            ]
        ];
     
    }
    
    
}
/* End of Class */
