<?php
namespace IComeFromTheNet\VoucherNum\Test\Base;

use PDO;
use PHPUnit\DbUnit\Operation\Composite;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\Operation\Factory;
use PHPUnit\DbUnit\TestCaseTrait;
use IComeFromTheNet\VoucherNum\Test\Base\DBOperationSetEnv;

class TestWithFixture extends TestCase
{
    
    use TestCaseTrait;
    
   
    // ----------------------------------------------------------------------------
    
    /**
    * @var PDO only instantiate pdo once for test clean-up/fixture load
    * @access private
    */
    static private $pdo = null;

    /**
    * @var \Doctrine\DBAL\Connection
    * @access private
    */
    static private $doctrineConnection;
    
    /**
    * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection only instantiate once per test
    * @access private
    */
    private $conn = null;
    
    
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DEMO_DATABASE_DSN'], $GLOBALS['DEMO_DATABASE_USER'], $GLOBALS['DEMO_DATABASE_PASSWORD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DEMO_DATABASE_SCHEMA']);
        }

        return $this->conn;
    }

    
   protected function getSetUpOperation()
    {
        return new Composite([
            new DBOperationSetEnv('foreign_key_checks',0),
            Factory::CLEAN_INSERT(),
            new DBOperationSetEnv('foreign_key_checks',1),
        ]);
    }
    
    
    public function getDataSet()
    {
       
    }
    
    
    /**
    * Gets a db connection to the test database
    *
    * @access protected
    * @return \Doctrine\DBAL\Connection
    */
    protected function getDoctrineConnection()
    {
        if(self::$doctrineConnection === null) {
        
            $config = new \Doctrine\DBAL\Configuration();
            
            $connectionParams = array(
                'dbname' => $GLOBALS['DEMO_DATABASE_SCHEMA'],
                'user' => $GLOBALS['DEMO_DATABASE_USER'],
                'password' => $GLOBALS['DEMO_DATABASE_PASSWORD'],
                'host' => $GLOBALS['DEMO_DATABASE_HOST'],
                'driver' => $GLOBALS['DEMO_DATABASE_TYPE'],
                'port'   => $GLOBALS['DEMO_DATABASE_PORT'],
            );
        
           self::$doctrineConnection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        }
        
        return self::$doctrineConnection;
        
    }
    
        
   


}
/* End of File */