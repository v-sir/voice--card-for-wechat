#!/bin/bash

step=2 

for((i=0;i<60;i=(i+step)));do
		$(php '/sky31/www/card.sky31.com/timer.php')
		sleep $step
done
exit 0


