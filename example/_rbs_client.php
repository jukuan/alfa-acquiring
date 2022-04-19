<?php

use AlfaAcquiring\RbsClient;

if (file_exists('_rbs_client.local.php')) {
    return require '_rbs_client.local.php';
}

return new RbsClient('test-api', 'test');
