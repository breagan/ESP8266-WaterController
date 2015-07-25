srv=net.createServer(net.TCP)   --  creats server	 
srv:listen(80,function(conn)    --  using port 80  waiting for header from webpage.
    conn:on("receive",function(conn,payload)   --   when header is recieved, saved in payload2
    print(payload);    --  prints header for dubugging  comment out for production.
    payload2 = payload
     
    conn:send("<!DOCTYPE html>")                    --   information sent back to webpage.
    conn:send("<html><body bgcolor='#FFFCEA'>")
    -- conn:send("<h2>ID:"..node.chipid())
    conn:send(wifi.sta.getmac())
    conn:send("<br>:"..wifi.sta.getip())
    -- conn:send("<br>Heap:"..node.heap())
    conn:send("<br>:zone0:"..gpio.read(zone0))
    conn:send("<br>:zone1:"..gpio.read(zone1))
    conn:send("<br>:zone2:"..gpio.read(zone2))
    conn:send("</body></html>")
    conn:close()
     print(node.heap())
--payload = nil
collectgarbage();
 if(payload2) then stoptest = string.find(payload2,"stop") end           --  checks header for 'stop' text.          
 if(stoptest) then dofile("stop.lua")                                    --  if stop then  run the stop file, resets all gpios to LOW
 print("timers stopped")
 payload2 = nil															 --  clears payload2	
 end
 
 if(payload2) then commandtest = string.find(payload2, "zone0=") end     -- looks for zone0 text in header.  Saves in var commantest.  
 if(commandtest) then 													 -- if zone0 is found run control.lua file.  
          totalendtime = nil
          totaltimestart = nil
          endtime = nil
          dofile("control.lua") end
          payload2 = nil 
 end)
end)
collectgarbage();

print("server running..")
