<?php


$kmc = "compressed_kmc";
$VALUE = str_repeat('x', 5000);

# Our couchbase handle
$cb = new Couchbase("10.0.0.99");

# Our memcached handle
$mc = new Memcached();
$mc->addServer("10.0.0.99", 11211);

function get_uncompressed($k) {
    global $cb;
    $cb->setOption(COUCHBASE_OPT_IGNOREFLAGS, 1);
    $v = $cb->get($k);
    $cb->setOption(COUCHBASE_OPT_IGNOREFLAGS, 0);
    return $v;
}

function cmp_values($k) {
    global $cb;
    global $mc;

    $mc_recvd = $mc->get($k);
    $cb_recvd = $cb->get($k);
    printf("Received values for '%s' match? %d\n",
        $k,
        $mc_recvd === $cb_recvd);
    printf("MC:\n");
    var_dump($mc_recvd);
    printf("CB:\n");
    var_dump($cb_recvd);
}


$mc->set($kmc, $VALUE);

# Ensure memcached compressed the value..
printf("Memcached compressed contents of '%s' to %d bytes\n",
    $kmc, strlen(get_uncompressed($kmc)));

# Get the value via memcached
cmp_values($kmc);

$kcb = "compressed_kcb";
# Ensure we can compress
ini_alter("couchbase.compression_threshold", 10);
ini_alter("couchbase.compression_factor", 0.1);

$cb->setOption(COUCHBASE_OPT_COMPRESSION, COUCHBASE_COMPRESSION_FASTLZ);

$cb->set($kcb, $VALUE);
# Assure that the value is indeed compressed.
printf("Couchbase compressed contents of '%s' to %d bytes\n",
    $kcb, strlen(get_uncompressed($kcb)));

cmp_values($kcb);
