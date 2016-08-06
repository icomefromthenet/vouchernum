<?php
namespace IComeFromTheNet\VoucherNum\Bus\Listener;

/**
 * Allows a command to define an event to emit when the command
 * has completed.
 * 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0
 */ 
interface HasEventInterface 
{
    
    /**
     * Build the event to emit
     * 
     * @return IComeFromTheNet\VoucherNum\Bus\Listener\CommandEvent
     */ 
    public function getEvent();
    
    /**
     * Name of the event to emit
     * 
     * @return string 
     */ 
    public function getEventName();
    
}
/* End if File */