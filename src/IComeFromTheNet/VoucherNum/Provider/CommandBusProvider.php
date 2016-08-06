<?php 
namespace IComeFromTheNet\VoucherNum\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\Locator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\CommandEvents\EventMiddleware;
use League\Tactician\CommandEvents\Event\CommandHandled;
use Bezdomni\Tactician\Pimple\PimpleLocator;

use IComeFromTheNet\VoucherNum\VoucherContainer;
use IComeFromTheNet\VoucherNum\Bus\Listener\CommandHandled as CustomHandler;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ValidatePropMiddleware;
use IComeFromTheNet\VoucherNum\Bus\Middleware\ExceptionWrapperMiddleware;


use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\NewVoucherTypeCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ReviseVoucherTypeCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Command\ExpireVoucherTypeCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Handler\NewVoucherTypeHandler;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Handler\ReviseVoucherTypeHandler;
use IComeFromTheNet\VoucherNum\Model\VoucherType\Handler\ExpireVoucherTypeHandler;

use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\CreateVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\RemoveVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Command\ReviseVoucherGroupCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler\CreateVoucherGroupHandler;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler\RemoveVoucherGroupHandler;
use IComeFromTheNet\VoucherNum\Model\VoucherGroup\Handler\ReviseVoucherGroupHandler;

use IComeFromTheNet\VoucherNum\Model\VoucherInstance\Command\CreateVoucherCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherInstance\Handler\CreateVoucherHandler;

use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\CreateVoucherRuleCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Command\ReviseVoucherRuleCommand;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Handler\CreateVoucherRuleHandler;
use IComeFromTheNet\VoucherNum\Model\VoucherGenRule\Handler\ReviseVoucherRuleHandler;


/**
 * Will bootstrap the db schema
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
class CommandBusProvider implements ServiceProviderInterface
{
    
     
     
     public function __construct()
     {
     
     
         
     }
     
     
     public function register(Container $pimple)
     {
       
        
        $pimple['commandBus.handler'] = function($c) {
            return new CustomHandler($c->getEventDispatcher());
        };
        
        # Command Bus
            
        $pimple['commandBus'] = function($c){
                
                $aLocatorMap = array_merge(
                        $this->getVoucherGroupMap($c),
                        $this->getVoucherInstanceMap($c),
                        $this->getVoucherRuleMap($c),
                        $this->getVoucherTypeMap($c)
                );
                
                // Create the Middleware that loads the commands
             
                $oCommandNamingExtractor = new ClassNameExtractor();
                $oCommandLoadingLocator  = new PimpleLocator($c, $aLocatorMap);
                $oCommandNameInflector   = new HandleInflector();
                    
                $oCommandMiddleware      = new CommandHandlerMiddleware($oCommandNamingExtractor,$oCommandLoadingLocator,$oCommandNameInflector);
                
                // Create exrta Middleware 
 
                $oEventMiddleware       = new EventMiddleware();
                $oEventMiddleware->addListener(
                	'command.handled',
                	function (CommandHandled $event) use ($c) {
                    	$c['commandBus.handler']->handle($event);
                	}
                );
                
                $oLockingMiddleware     = new LockingMiddleware();
                $oValdiationMiddleware  = new ValidatePropMiddleware();
                $oExceptionMiddleware   = new ExceptionWrapperMiddleware();
               
        
                // create the command bus
        
                $oCommandBus = new CommandBus([
                            $oExceptionMiddleware,
                            $oEventMiddleware,
                            $oLockingMiddleware,
                            $oValdiationMiddleware,
                            $oCommandMiddleware
                ]);
                
                return $oCommandBus;
                
        };
     
   
     
     }
    
    
         
    /**
     * Return array of operations used in VoucherGroup CRUD
     * 
     * @return array
     * 
     */ 
    public function getVoucherGroupMap(Container $c)
    {
        $c['vouchergroup.handler.create'] = function(Container $c) {
            return new CreateVoucherGroupHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_GROUP), $c->getNow());
        };
        
        $c['vouchergroup.handler.revise'] = function(Container $c) {
            return new ReviseVoucherGroupHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_GROUP), $c->getNow());
        };
        
        $c['vouchergroup.handler.remove'] = function(Container $c) {
            return new RemoveVoucherGroupHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_GROUP), $c->getNow());
        };
        
        
        return [
            CreateVoucherGroupCommand::class => 'vouchergroup.handler.create',
            ReviseVoucherGroupCommand::class => 'vouchergroup.handler.revise',
            RemoveVoucherGroupCommand::class => 'vouchergroup.handler.remove',
        ];
        
    }
    
    /**
     * Return array of operations used in VoucherInstance CRUD
     * 
     * @return array
     * 
     */
    public function getVoucherInstanceMap(Container $c)
    {
        $c['voucherinstance.handler.create'] = function(Container $c) {
            return new CreateVoucherHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_INSTANCE), $c->getNow());
        };
        
        
        return [
            CreateVoucherCommand::class => 'voucherinstance.handler.create',
        ];
        
    }
    
    /**
     * Return array of operations used in VoucherGenRule CRUD
     *
     *  return array
     */
    public function getVoucherRuleMap(Container $c)
    {
       $c['vouchergenrule.handler.new'] = function(Container $c) {
            return new CreateVoucherRuleHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_RULE), $c->getNow());
        };
        
        $c['vouchergenrule.handler.revise'] = function(Container $c) {
            return new ReviseVoucherRuleHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_RULE), $c->getNow());
        };
        
        
        
        return [
            CreateVoucherRuleCommand::class    => 'vouchergenrule.handler.new', 
            ReviseVoucherRuleCommand::class    => 'vouchergenrule.handler.revise',
        ];
    }
    
    /**
     * Return array of operations used in VoucherType CRUD
     * 
     * @return array
     */
    public function getVoucherTypeMap(Container $c)
    {
        $c['vouchertype.handler.new'] = function(Container $c) {
            return new NewVoucherTypeHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE), $c->getNow());
        };
        
        $c['vouchertype.handler.revise'] = function(Container $c) {
            return new ReviseVoucherTypeHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE), $c->getNow());
        };
        
        $c['vouchertype.handler.expire'] = function(Container $c) {
            return new ExpireVoucherTypeHandler($c->getGateway(VoucherContainer::DB_TABLE_VOUCHER_TYPE), $c->getNow());
        };
        
        
        return [
            NewVoucherTypeCommand::class    => 'vouchertype.handler.new', 
            ExpireVoucherTypeCommand::class => 'vouchertype.handler.expire',
            ReviseVoucherTypeCommand::class => 'vouchertype.handler.revise',
        ];
        
    }
    
    
}
/* End of Class */