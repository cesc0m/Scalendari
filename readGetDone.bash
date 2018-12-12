#! /bin/bash

echo "Inserire areaID"

read areaID

commands="
POST /ScalendariV3/public/actions/getDone.php HTTP/1.0
Host: 192.168.1.2
Content-Type: application/x-www-form-urlencoded
Content-Length: 32"

echo "$commands" > telnet 192.168.1.2 80
