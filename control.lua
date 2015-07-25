collectgarbage()
tmr.stop(0)               -- stops all timers.
tmr.stop(1)
tmr.stop(2)
gpio.write(zone0, gpio.LOW)   -- turns off all gpios.
gpio.write(zone1, gpio.LOW)
gpio.write(zone2, gpio.LOW)
if(payload2 ==  nil) then return end          --  if payload2 is empty exit from this script.  This happens when server is a webpage sends a header wih no information
-- running script with no zone or time info will panic the contoller.    
zone0test = string.find(payload2, "zone0=")   -- find zone0 then save the time in var zone0time.
zone0time = string.sub(payload2,zone0test + 6, 14)
print(zone0time)								-- used in debugging.
zone1test = string.find(payload2, "zone1=")  --  find zone1 and save time in var zone1time.
zone1time = string.sub(payload2,zone1test + 6, 23)
print(zone1time)
zone2test = string.find(payload2, "zone2=")  --  find zone2 and save  time in var zone2time.
zone2time = string.sub(payload2,zone2test + 6, 32)
print(zone2time)  

if zone0time == "00" and zone1time == "00" and zone2time == "00" then   --  if all times are zero, stop the script.
return 
end

print(node.heap())

--  45 min  = 45 * 1000 * 60 //   45 * 60000  = 2 700 000

-- 100 converts input 45 to 4.5 seconds.  handy for debuging.
-- 60000 converts input 45 to 45 minuits
multiplyer = 100

totaltimestart = tmr.now(3)   -- used for debugging to verify timers are working correctly.

start = tmr.now(0)            -- debugging.

if zone0time == "00" then     -- need to send two digits in header for zonetime 00 us used for 0 or no time. 
     duration = 1 			  -- NodeMcu does math operations on intergers only with this build.  00 converts to 1 	
else
     duration = zone0time * multiplyer   -- all other numbers are multipled by multiplyer to set duration.
end




------------------------------------------------Zone0 
if duration ~= 1 then                  -- unless duration is 1 the gpio is set to high and the valve is turned on.
gpio.write(zone0, gpio.HIGH)

end
print("Zone0 on")
tmr.alarm(0, duration, 0, function()     --  timer is started and runs for the duration.   Script does nothing untill this timer reaches the duration time.
endtime = tmr.now(0)                     --  
print("  timer0 duration: "..endtime - start)   
print("Zone0 off")
gpio.write(zone0, gpio.LOW)             --  gpio is reset to LOW and valve is turned off.
tmr.delay(500000)                       --  A short delay to ensure that only one valve is operated at a time. 
										
 ------------------------------------------------Zone1    
	 
	 
	 if zone1time == "00" then			--  Repeat for the zones 1 
          duration = 1 
          --print("duration: "..duration) 
     else
         duration = zone1time * multiplyer
     end
     start = tmr.now(1)
     if duration ~= 1 then
     gpio.write(zone1, gpio.HIGH)
     print("Zone1 on") 
      end
     
     tmr.alarm(0, duration, 0, function() 
     endtime = tmr.now(1)
     print("  timer1 duration: "..endtime - start)
     print("Zone1 off")
     gpio.write(zone1, gpio.LOW) 
     tmr.delay(500000)         
   
 ------------------------------------------------Zone2        
         
         if zone2time == "00" then       --  Repeat for the zones 2 
          duration = 1 
         else
          duration = zone2time * multiplyer
         end
          print("Zone2 on")
          if duration ~= 1 then
          gpio.write(zone2, gpio.HIGH)
          end
          start = tmr.now(2)
          tmr.alarm(0, duration, 0, function() 
          endtime = tmr.now(2)
          print("  timer2 duration: "..endtime - start)
          print("Zone2 off")
          gpio.write(zone2, gpio.LOW)
          
totalendtime = tmr.now(3)                                      
print("total duration: "..(totalendtime - totaltimestart))           ---  debugging 
print(node.heap())          
          end)
     end)
end)

collectgarbage()
