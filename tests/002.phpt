--TEST--
Check for couchbase_connect
--SKIPIF--
<?php include "skipif.inc" ?>
--FILE--
<?php
include "couchbase.inc";
$handle = couchbase_connect(COUCHBASE_CONFIG_HOST, COUCHBASE_CONFIG_USER, COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);
var_dump($handle);
$handle = couchbase_connect(COUCHBASE_CONFIG_HOST, '80', COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);

$handle = new Couchbase(COUCHBASE_CONFIG_HOST, COUCHBASE_CONFIG_USER, COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);
print_r($handle);

$oo = new Couchbase('127.0.0.1:1','bad-user','bad-password','does-not-exist-bucket');
print($oo->getResultCode() . "\n");
print($oo->getResultMessage() . "\n");
?>
--EXPECTF--
resource(%d) of type (Couchbase)

Warning: Failed to establish libcouchbase connection to server: Authentication error in %s002.php on line %d
Couchbase Object
(
    [%s] => Resource id #%d
)

Warning: Failed to establish libcouchbase connection to server: Connection failure in %s002.php on line %d
23
Connection failure
