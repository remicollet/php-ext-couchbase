--TEST--
Check for connecting to multiple servers
--SKIPIF--
<?php
include "skipif.inc";
include "couchbase.inc";

if (!defined("COUCHBASE_CONFIG_HOST_ALT")) {
  die ("skip only valid when running clustered");
}

?>
--FILE--
<?php
include "couchbase.inc";
// check for working
$handle = couchbase_connect(COUCHBASE_CONFIG_HOST . ';' .
  COUCHBASE_CONFIG_HOST_ALT, COUCHBASE_CONFIG_USER, COUCHBASE_CONFIG_PASSWD,
  COUCHBASE_CONFIG_BUCKET);
var_dump($handle);

// check that it won't work too
$handle_f = couchbase_connect('bogus-' . COUCHBASE_CONFIG_HOST . ';' .
  'bogus-' . COUCHBASE_CONFIG_HOST_ALT, COUCHBASE_CONFIG_USER,
   COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);

// check that it works with one bogus
$handle_pf = couchbase_connect('bogus-' . COUCHBASE_CONFIG_HOST . ';' .
  COUCHBASE_CONFIG_HOST, COUCHBASE_CONFIG_USER,
  COUCHBASE_CONFIG_PASSWD, COUCHBASE_CONFIG_BUCKET);

// check OO approach
$handle_oo = new Couchbase(COUCHBASE_CONFIG_HOST . ';' .
  COUCHBASE_CONFIG_HOST_ALT, COUCHBASE_CONFIG_USER, COUCHBASE_CONFIG_PASSWD,
  COUCHBASE_CONFIG_BUCKET);
print_r($handle_oo);

?>
--EXPECTF--
resource(%d) of type (Couchbase)

Warning: couchbase_connect(): Failed to connect libcouchbase to server: Unknown host in %s035.php on line %d
Couchbase Object
(
    [%s] => Resource id #%d
)
