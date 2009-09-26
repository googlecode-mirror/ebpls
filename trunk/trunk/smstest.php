<?php

$r = exec("echo AT>/dev/ttyS0 2>&1");
echo $r."===";

