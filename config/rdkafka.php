<?php
switch(get_cfg_var('env')){
case 'develop':
    return "192.168.20.171";
case 'testing':
    return "193.168.10.101,193.168.10.102,193.168.10.103";
case 'production':
    return "192.168.21.22,192.168.21.24,192.168.21.25";
}
