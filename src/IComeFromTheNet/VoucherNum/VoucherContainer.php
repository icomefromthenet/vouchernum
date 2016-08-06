<?php
namespace IComeFromTheNet\VoucherNum;

use DateTime;
use Pimple\Container; 
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Valitron\Validator;
use DBALGateway\Table\GatewayProxyCollection;

use IComeFromTheNet\VoucherNum\Provider\DBGatewayProvider;
use IComeFromTheNet\VoucherNum\Provider\CommandBusProvider;

/**
 * Voucher Service Container
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */ 
class VoucherContainer extends Container
{
    
    /**
     *  These constants are the internal names of the table
     *  and are the key columns in the map
     */
     
    const DB_TABLE_VOUCHER_TYPE     = 'vo_voucher_type' ;
    const DB_TABLE_VOUCHER_GROUP    = 'vo_voucher_group' ;
    const DB_TABLE_VOUCHER_INSTANCE = 'vo_voucher_instance' ;
    const DB_TABLE_VOUCHER_RULE     = 'vo_voucher_gen_rule';
    
    
    
    protected function getDefaultTableMap()
    {
        
        return array(
            self::DB_TABLE_VOUCHER_GROUP    => 'vo_voucher_group',
            self::DB_TABLE_VOUCHER_INSTANCE => 'vo_voucher_instance',
            self::DB_TABLE_VOUCHER_RULE     => 'vo_voucher_gen_rule',       
            self::DB_TABLE_VOUCHER_TYPE     => 'vo_voucher_type',
            
        );
        
    }
    
    
    /**
     * Short cust to fetch database gateways classes from the factory
     * 
     * @return D
     */ 
    public function getGateway($sGateway)
    {
        return $this->getGatewayFactory()->getGateway($sGateway);
    }
    
    public function getGatewayProxyCollection()
    {
        return $this['gatewayProxyCollection'];
    }
    
    /**
     * DI Container constrcutor
     * 
     * @param Doctrine\DBAL\Connection  $db The Database connection
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $oEvent    The event dispatcher
     * @param Psr\Log\LoggerInterface   $oLogger    The App Logger
     * @param GatewayProxyCollection    $col    A collection to hold the gateways
     */ 
    public function __construct(Connection $db, EventDispatcherInterface $oEvent, LoggerInterface $oLogger, GatewayProxyCollection $col) 
    {
        $this['database']    = $db;
        $this['event']       = $oEvent;
        $this['gatewayProxyCollection'] = $col;
        $this['logger']      = $oLogger;
    }
    
    
    /**
     * Return the assigned database adapter
     * 
     * @return Doctrine\DBAL\Connection
     */ 
    public function getDatabaseAdapter()
    {
        return $this['database'];
    }
    
    /**
     * Return the assigned event dispatcher
     * 
     * @return Symfony\Component\EventDispatcher\EventDispatcherInterface
     */ 
    public function getEventDispatcher()
    {
        return $this['event'];
    }
    
    /**
     * Return the assigned event dispatcher
     * 
     * @return Psr\Log\LoggerInterface
     */ 
    public function getAppLogger()
    {
        return $this['logger'];
    }
    
    
    
    /**
     *  Return the proxy gateway collection
     * 
     * @access public
     * @return IComeFromTheNet\Ledger\GatewayProxyCollection
     */ 
    public function getGatewayFactory()
    {
        return $this['gatewayProxyCollection'];
    }
    

    /**
     * Fetch the assigned now date
     * 
     * return DateTime
     */ 
    public function getNow()
    {
        return  $this['now'];
    }
    
    
    
    /**
     * Returns the command bus
     * 
     * @return League\Tactician\CommandBus
     */ 
    public function getCommandBus()
    {
        return $this['commandBus'];
    }
    
    
    //--------------------------------------------------------------------------
    # Service Bootstrap
    
    
    protected function getServiceProviders()
    {
        return [
             new DBGatewayProvider($this->getDefaultTableMap(), $this->getGatewayProxyCollection()->getSchema(), $this->getGatewayProxyCollection()),
             new CommandBusProvider(),
        ];
        
    }
    
    
    /**
     *  Build this services dependecies, only call once
     *  
     * @return void
     */ 
    public function boot(DateTime $now, $aTableMap = array())
    {
        
        $this['now']      = $now;
        $this['tablemap'] = $aTableMap;

        
        $oProviders = $this->getServiceProviders();
        
        foreach($oProviders as $oProvider)
        {
            $oProvider->register($this);
        }
        
        
    }
    
}
/* End of File */