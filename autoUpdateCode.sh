#!/bin/bash
step=10
while ((1));
  do
    cd /data/wwwroot/$1
    git stash && git clean -f -d && git pull && php73 artisan l5-swagger:generate
    sleep $step
  done
  