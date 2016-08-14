<?php
namespace IComeFromTheNet\VoucherNum\Test;

use DateTime;
use Mrkrstphr\DbUnit\DataSet\ArrayDataSet;
use IComeFromTheNet\VoucherNum\VoucherException;
use IComeFromTheNet\VoucherNum\VoucherGenerator;
use IComeFromTheNet\VoucherNum\Model\VoucherType\VoucherType;

class VoucherGeneratorTest extends VoucherTestAbstract
{
    
    public function getDataSet()
    {
       return new ArrayDataSet([
           __DIR__.'/Fixture/VoucherFixture.php',
        ]);
    }
    
    
    public function testVoucherFindByName()
    {
        $oContainer   = $this->getContainer();
        $oServiceMock = $this->getMockBuilder('IComeFromTheNet\\VoucherNum\\VoucherGenerator')
                         ->setMethods(['loadVoucher'])
                         ->setConstructorArgs([$oContainer])
                         ->getMock();
        
        $oServiceMock->expects($this->once())
                      ->method('loadVoucher')
                      ->with($this->isInstanceOf('IComeFromTheNet\\VoucherNum\\Model\\VoucherType\\VoucherType'));
        
        
        $oServiceMock->setVoucherByName('fixture_type_a');
        
        
    }
    
    
    public function testVoucherFindById()
    {
        $oContainer   = $this->getContainer();
        $oServiceMock = $this->getMockBuilder('IComeFromTheNet\\VoucherNum\\VoucherGenerator')
                         ->setMethods(['loadVoucher'])
                         ->setConstructorArgs([$oContainer])
                         ->getMock();
        
        $oServiceMock->expects($this->once())
                      ->method('loadVoucher')
                      ->with($this->isInstanceOf('IComeFromTheNet\\VoucherNum\\Model\\VoucherType\\VoucherType'));
                      
        $oServiceMock->setVoucherById(1);   
        
    }
    
    /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExceptionMessage  Unable to find voucher with name :: aa
     */ 
    public function testVoucherFindByNameFailsBadName() {
        $oContainer = $this->getContainer();
        $oService   = new VoucherGenerator($oContainer);
        
          $oService->setVoucherByName('aa');
    }
    
     /**
     * @expectedException IComeFromTheNet\VoucherNum\VoucherException
     * @expectedExceptionMessage  Unable to find voucher at id :: 1000
     */ 
    public function testVoucherFindByIdFailesBadId()
    {
        $oContainer = $this->getContainer();
        $oService   = new VoucherGenerator($oContainer);
        
        $oService->setVoucherById(1000);
    }
    
    
    public function testGenerateSuccess()
    {
        $oContainer   = $this->getContainer();
        $oService     = new VoucherGenerator($oContainer);
        
        $oService->setVoucherByName('fixture_type_a');
        
        $mVoucherNumberOne = $oService->generate();
        $mVoucherNumberTwo = $oService->generate();
        $mVoucherNumberThree = $oService->generate();
        $mVoucherNumberFour = $oService->generate();
        $mVoucherNumberFive = $oService->generate();
        
        
        $this->assertEquals('a_1_b',$mVoucherNumberOne);
        $this->assertEquals('a_2_b',$mVoucherNumberTwo);
        $this->assertEquals('a_3_b',$mVoucherNumberThree);
        $this->assertEquals('a_4_b',$mVoucherNumberFour);
        $this->assertEquals('a_5_b',$mVoucherNumberFive);
        
        $this->assertEquals($mVoucherNumberFive,$oService->lastResult());
        
        
    }
    
    public function testGenerateSuccessWithSeed()
    {
        $oContainer   = $this->getContainer();
        $oService     = new VoucherGenerator($oContainer);
        
        $oService->setVoucherByName('fixture_type_a');
        
        $mVoucherNumberOne = $oService->generate(5);
        $mVoucherNumberTwo = $oService->generate(6);
        $mVoucherNumberThree = $oService->generate(7);
        $mVoucherNumberFour = $oService->generate(8);
        $mVoucherNumberFive = $oService->generate(9);
        
        
        $this->assertEquals('a_5_b',$mVoucherNumberOne);
        $this->assertEquals('a_6_b',$mVoucherNumberTwo);
        $this->assertEquals('a_7_b',$mVoucherNumberThree);
        $this->assertEquals('a_8_b',$mVoucherNumberFour);
        $this->assertEquals('a_9_b',$mVoucherNumberFive);
        
        $this->assertEquals($mVoucherNumberFive,$oService->lastResult());
        
        
    }
    
}
/* End of class */