<?php

$working = '/mnt/dev/dev.laits.utexas.edu/facpubs/rev2';
$target = '/mnt/www/laits.utexas.edu/facpubs';
print "copying $working/* to $target\n";

system("rsync -ar --delete --exclude='bin' --exclude='.git' --exclude='files/cache/*' --exclude='files/log/*' -e ssh $working/* $target");


