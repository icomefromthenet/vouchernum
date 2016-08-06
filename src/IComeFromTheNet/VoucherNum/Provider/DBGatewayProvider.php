<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\DBAL\Schema\Schema;
use DBALGateway\Table\GatewayProxyCollection;

use IComeFromTheNet\VoucherNum\VoucherContainer;

use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroupBuilder;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\VoucherGroupGateway;

use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeGateway;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherTypeBuilder;

use IComeFromTheNet\VoucherNum\Model\VoucherInstance\VoucherInstanceBuilder;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\VoucherInstanceGateway;

use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRuleBuilder;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\VoucherGenRuleGateway;

/**
 * Will bootstrap the db schema
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class DBGatewayProvider implements ServiceProviderInterface
{
    
     
     protected $aDefaultTableMap;
     
     protected $oGatewayProxyCollection;
     
     protected $oSchema;
     
     
     
     public function __construct(array $aDefaultTableMap, Schema $oSchema,  GatewayProxyCollection $oGatewayProxyCollection)
     {
         $this->aDefaultTableMap          = $aDefaultTableMap;
         $this->oGatewayProxyCollection   = $oGatewayProxyCollection;
         $this->oSchema                   = $oSchema;
         
     }
     
     
     public function register(Container $pimple)
     {
       
        $c                  = $pimple;
        $oGatewayProxyColl  = $this->oGatewayProxyCollection;
        $aDefaultTableMap   = $this->aDefaultTableMap;
        $oSchema            = $this->oSchema;
         
        $aTableMap          = array_merge($c['tablemap'],$aDefaultTableMap);
        
        
        
        $oGatewayProxyColl->addGateway(VoucherContainer::DB_TABLE_VOUCHER_GROUP, function() use ($c,$oSchema,$aTableMap) {
            
            $sTableName = $aTableMap[VoucherContainer::DB_TABLE_VOUCHER_GROUP];
            
            $table = $oSchema->createTable($sTableName);
            $table->addColumn('voucher_group_id','integer',array("unsigned" => true,'autoincrement' => true));
            $table->addColumn('voucher_group_name','string',array("length" => 100));
            $table->addColumn('voucher_group_slug','string',array("length" => 100));
            $table->addColumn('is_disabled','boolean',array("default"=>false));
            $table->addColumn('sort_order','integer',array("unsigned" => true));
            $table->addColumn('date_created','datetime',array());
            
            $table->setPrimaryKey(array('voucher_group_id'));
            $table->addUniqueIndex(array('voucher_group_slug'),'gl_voucher_group_uiq1');
        
            $sAlias = 'a';
            
            # connection
            $oConnection = $c->getDatabaseAdapter();
            
            # builder
            $oBuilder = new VoucherGroupBuilder();
            $oBuilder->setTableQueryAlias($sAlias);
            
            
            # event
            $oEvent  = $c->getEventDispatcher();
            
            $oGateway = new VoucherGroupGateway($sTableName,$oConnection,$oEvent,$table,null,$oBuilder);
            $oGateway->setTableQueryAlias($sAlias);
            $oGateway->setGatewayCollection($c->getGatewayFactory());
            
            return  $oGateway;
            
        });
        
        $oGatewayProxyColl->addGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE , function() use ($c,$oSchema,$aTableMap) {
            $sTableName = $aTableMap[VoucherContainer::DB_TABLE_VOUCHER_TYPE];
            
            
            # Voucher Type Table
            $table = $oSchema->createTable($sTableName);
            $table->addColumn('voucher_type_id','integer',array("unsigned" => true,'autoincrement' => true));
            $table->addColumn("voucher_enabled_from", "datetime",array());
            $table->addColumn("voucher_enabled_to", "datetime",array());
            $table->addColumn('voucher_name','string',array('length'=>100));
            $table->addColumn('voucher_name_slug','string',array('length'=>100));
            $table->addColumn('voucher_description','string',array('length'=>500));
            $table->addColumn('voucher_group_id','integer',array('unsigned'=> true));
            $table->addColumn('voucher_gen_rule_id','integer',array('unsigned'=> true));
            
            
            $table->setPrimaryKey(array('voucher_type_id'));
            $table->addForeignKeyConstraint($aTableMap[VoucherContainer::DB_TABLE_VOUCHER_GROUP] ,array('voucher_group_id'),array('voucher_group_id'),array(),'gl_voucher_type_fk1');
            $table->addForeignKeyConstraint($aTableMap[VoucherContainer::DB_TABLE_VOUCHER_RULE],array('voucher_gen_rule_id'),array('voucher_gen_rule_id'),array(),'gl_voucher_type_fk2s');
            $table->addUniqueIndex(array('voucher_name','voucher_enabled_from'),'gl_voucher_type_uiq1');

            
             $sAlias = 'd';
            
            # connection
            $oConnection = $c->getDatabaseAdapter();
            
            # builder
            $oBuilder = new VoucherTypeBuilder();
            $oBuilder->setTableQueryAlias($sAlias);
            
            
            # event
            $oEvent  = $c->getEventDispatcher();
            
            $oGateway = new VoucherTypeGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE,$oConnection,$oEvent,$table,null,$oBuilder);
            $oGateway->setTableQueryAlias($sAlias);
            $oGateway->setGatewayCollection($c->getGatewayFactory());
            
            return  $oGateway;
            
        });
        
        $oGatewayProxyColl->addGateway(VoucherContainer::DB_TABLE_VOUCHER_INSTANCE,function() use ($c,$oSchema,$aTableMap) {
            $sTableName = $aTableMap[VoucherContainer::DB_TABLE_VOUCHER_INSTANCE];
            
            # Vouchers Table (Instance Table)
            $table = $oSchema->createTable($sTableName);
            $table->addColumn('voucher_instance_id','integer',array("unsigned" => true,'autoincrement' => true));
            $table->addColumn('voucher_type_id','integer',array("unsigned" => true));
            $table->addColumn('voucher_code','string',array("length"=> 255));
            $table->addColumn('date_created','datetime',array());
            
            $table->setPrimaryKey(array('voucher_instance_id'));
            $table->addForeignKeyConstraint($aTableMap[VoucherContainer::DB_TABLE_VOUCHER_TYPE],array('voucher_type_id'),array('voucher_type_id'),array(),'gl_voucher_instance_fk1');
            $table->addUniqueIndex(array('voucher_code'),'gl_voucher_instance_uiq1');

            
            
            $sAlias = 'c';
            
            # connection
            $oConnection = $c->getDatabaseAdapter();
            
            
            # builder
            $oBuilder = new VoucherInstanceBuilder();
            $oBuilder->setTableQueryAlias($sAlias);
            
            
            # event
            $oEvent  = $c->getEventDispatcher();
            
            $oGateway = new VoucherInstanceGateway($sTableName,$oConnection,$oEvent,$table,null,$oBuilder);
            $oGateway->setTableQueryAlias($sAlias);
            $oGateway->setGatewayCollection($c->getGatewayFactory());
            
            return  $oGateway;
            
        });
        
        $oGatewayProxyColl->addGateway(VoucherContainer::DB_TABLE_VOUCHER_RULE ,function() use ($c,$oSchema,$aTableMap,$oGatewayProxyColl) {
            $sTableName = $aTableMap[VoucherContainer::DB_TABLE_VOUCHER_RULE];
            
              # Voucher Rules
            $table = $oSchema->createTable($sTableName);
            $table->addColumn('voucher_rule_name','string',array('length'=> 25));
            $table->addColumn('voucher_rule_slug','string',array("length" => 25));
            $table->addColumn('voucher_gen_rule_id','integer',array('unsigned'=> true,'autoincrement' => true));
            $table->addColumn('voucher_padding_char','string',array('legnth'=>'1'));
            $table->addColumn('voucher_prefix','string',array('length'=> 50));
            $table->addColumn('voucher_suffix','string',array('length'=>50));
            $table->addColumn('voucher_length','smallint',array('unsigned'=> true,'length'=>3));
            $table->addColumn('date_created','datetime',array());
            $table->addColumn('voucher_sequence_no','integer',array('unsigned'=> true));
            $table->addColumn('voucher_sequence_strategy','string',array('length'=> 20));
            
            
            $table->setPrimaryKey(array('voucher_gen_rule_id'));
            
            $sAlias = 'b';
            
            # connection
            $oConnection = $c->getDatabaseAdapter();
            
            # builder
            $oBuilder = new VoucherGenRuleBuilder();
            $oBuilder->setTableQueryAlias($sAlias);
            
            
            # event
            $oEvent  = $c->getEventDispatcher();
            
            $oGateway = new VoucherGenRuleGateway($sTableName,$oConnection,$oEvent,$table,null,$oBuilder);
            $oGateway->setTableQueryAlias($sAlias);
            $oGateway->setGatewayCollection($c->getGatewayFactory());
            
            return  $oGateway;
            
        });
     
     }
    
    
}
/* End of Class */