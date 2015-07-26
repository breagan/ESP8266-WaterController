--  Sets all gpios to LOW and resets variables.
tmr.stop(0)
tmr.stop(1)
tmr.stop(2)
gpio.write(zone0, gpio.LOW)
gpio.write(zone1, gpio.LOW)
gpio.write(zone2, gpio.LOW)
duration = nil
multiplyer = nil
zone0time = nil
zone1time = nil
zone2time = nil
collectgarbage()
