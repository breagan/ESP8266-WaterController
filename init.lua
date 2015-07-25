wifi.setmode(wifi.STATION);
wifi.sta.config("SSID","password"); --  Enter your SSID and password here.
cfg = {ip="192.168.11.44",netmask="255.255.255.0",gateway="192.168.11.1"};  -- IP number for your ESP and gateway.  Use a static number and reserve it in your router. 
wifi.sta.setip(cfg);   -- sends ip to ESP
zone0 = 1      --gpio4  markings are reversed on chip with gpio5
zone1 = 5      --gpio14
zone2 = 6      --gpio12
gpio.mode(zone0, gpio.OUTPUT)   -- set gpios to output and write them low.  
gpio.write(zone0, gpio.LOW)
gpio.mode(zone1, gpio.OUTPUT)
gpio.write(zone1, gpio.LOW)
gpio.mode(zone2, gpio.OUTPUT)
gpio.write(zone2, gpio.LOW)

collectgarbage()
dofile("servernode.lua") --  starts the server script.  comment this out while depugging.  Start the server manually untill you are sure it is working.  Avoid death loop.
