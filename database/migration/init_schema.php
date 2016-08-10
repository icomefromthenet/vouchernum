<?php
namespace Migration\Components\Migration\Entities;

use Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\AbstractSchemaManager as Schema,
    Doctrine\DBAL\Schema\Schema as ASchema,
    Migration\Components\Migration\EntityInterface;

class init_schema implements EntityInterface
{

   
    
    
    public function buildSchema(Connection $db, ASchema $schema)
    {
        # Voucher Groups
        $table = $schema->createTable("vo_voucher_group");
        $table->addColumn('voucher_group_id','integer',array("unsigned" => true,'autoincrement' => true));
        $table->addColumn('voucher_group_name','string',array("length" => 100));
        $table->addColumn('voucher_group_slug','string',array("length" => 100));
        $table->addColumn('is_disabled','boolean',array("default"=>false));
        $table->addColumn('sort_order','integer',array("unsigned" => true));
        $table->addColumn('date_created','datetime',array());
        
        $table->setPrimaryKey(array('voucher_group_id'));
        $table->addUniqueIndex(array('voucher_group_slug'),'vo_voucher_group_uiq1');
        
        
        # Voucher Rules
        $table = $schema->createTable("vo_voucher_gen_rule");
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
        $table->addColumn('voucher_validate_rules','array',array());
        
        
        $table->setPrimaryKey(array('voucher_gen_rule_id'));
        
        # Voucher Type Table
        $table = $schema->createTable("vo_voucher_type");
        $table->addColumn('voucher_type_id','integer',array("unsigned" => true,'autoincrement' => true));
        $table->addColumn("voucher_enabled_from", "datetime",array());
        $table->addColumn("voucher_enabled_to", "datetime",array());
        $table->addColumn('voucher_name','string',array('length'=>100));
        $table->addColumn('voucher_name_slug','string',array('length'=>100));
        $table->addColumn('voucher_description','string',array('length'=>500));
        $table->addColumn('voucher_group_id','integer',array('unsigned'=> true));
        $table->addColumn('voucher_gen_rule_id','integer',array('unsigned'=> true));
        
        
        $table->setPrimaryKey(array('voucher_type_id'));
        $table->addForeignKeyConstraint('vo_voucher_group',array('voucher_group_id'),array('voucher_group_id'),array(),'vo_voucher_type_fk1');
        $table->addForeignKeyConstraint('vo_voucher_gen_rule',array('voucher_gen_rule_id'),array('voucher_gen_rule_id'),array(),'vo_voucher_type_fk2');
        $table->addUniqueIndex(array('voucher_name','voucher_enabled_from'),'vo_voucher_type_uiq1');
        
        # Vouchers Table (Instance Table)
        $table = $schema->createTable("vo_voucher_instance");
        $table->addColumn('voucher_instance_id','integer',array("unsigned" => true,'autoincrement' => true));
        $table->addColumn('voucher_type_id','integer',array("unsigned" => true));
        $table->addColumn('voucher_code','string',array("length"=> 255));
        $table->addColumn('date_created','datetime',array());
        
        $table->setPrimaryKey(array('voucher_instance_id'));
        $table->addForeignKeyConstraint('vo_voucher_type',array('voucher_type_id'),array('voucher_type_id'),array(),'vo_voucher_instance_fk1');
        $table->addUniqueIndex(array('voucher_code'),'vo_voucher_instance_uiq1');
        
        return $schema;
    }
    
    public function up(Connection $db, Schema $schema)
    {
        
        
        $schemahema = $this->buildSchema($db,new ASchema());
        
        $queries = $schemahema->toSql($db->getDatabasePlatform()); // get queries to create this schema.
        
        # execute setup queries
        foreach($queries as $query) {
            
            echo $query . PHP_EOL;
            $db->exec($query);    
        }
        
    }

    public function down(Connection $db, Schema $schema)
    {


    }


}
/* End of File */
