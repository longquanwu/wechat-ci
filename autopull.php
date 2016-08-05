<?php
header("Content-type: text/html; charset=utf-8");
$fp=fopen("./autopulllog.txt",'a');
exec("cd /data/web/CodeIgniter");//进入目录
exec("git pull origin master");//进行git拉取，前提是使用了ssh
fwrite($fp,"※".date('Y-m-d H:i:s')."\tautopull\t\n");//进行记录
