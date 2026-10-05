<?php

if (php_sapi_name() !== 'cli') {
    die("Error: This script can only be run from the command line (CLI).\n");
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Queue;

$args = array_slice($argv, 1);
$once = in_array('--once', $args, true);
$queueArgs = array_filter($args, function($a) { return $a !== '--once'; });
$queue = reset($queueArgs) ?: 'default';

echo "====================================================\n";
echo "   SAASIFY BACKGROUND QUEUE WORKER (CLI DAEMON)    \n";
echo "====================================================\n";
echo "Listening on queue: [{$queue}]\n";
echo "Mode: " . ($once ? "Run Once (--once)" : "Continuous Loop Daemon") . "\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";
echo "----------------------------------------------------\n";

if ($once) {
    $count = Queue::processAll($queue, 100);
    echo "Processed {$count} jobs from queue [{$queue}]. Worker exiting.\n";
    exit(0);
}

// Continuous Daemon Loop
$idleCount = 0;
while (true) {
    $result = Queue::processNext($queue);

    if ($result === true) {
        echo "[" . date('H:i:s') . "] [SUCCESS] Job completed.\n";
        $idleCount = 0;
    } elseif ($result === false) {
        echo "[" . date('H:i:s') . "] [FAILURE] Job failed and scheduled for retry.\n";
        $idleCount = 0;
    } else {
        // Queue empty, sleep 2 seconds
        $idleCount++;
        if ($idleCount % 15 === 0) {
            echo "[" . date('H:i:s') . "] Queue [{$queue}] is empty. Worker is idling...\n";
        }
        sleep(2);
    }
}
