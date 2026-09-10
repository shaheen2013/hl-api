<?php

namespace App\Logging;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Illuminate\Support\Arr;
use Monolog\Logger;

class CloudWatchLoggerFactory
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $sdkParams = $config["sdk"];
        $tags = Arr::get($config, "tags", []);
        $name = Arr::get($config, "name", 'cloudwatch');

        // Instantiate AWS SDK CloudWatch Logs Client
        $client = new CloudWatchLogsClient($sdkParams);

        // Log group name, will be created if none
        $groupName = Arr::get($config, 'group_name');

        // Log stream name, will be created if none
        $streamName = Arr::get($config, 'stream_name');

        // Days to keep logs, 14 by default. Set to `null` to allow indefinite retention.
        $retentionDays = Arr::get($config, "retention");

        $logLevel = Arr::get($config, 'level', 'info') == 'info' ? Logger::INFO : Logger::DEBUG;

        // Instantiate handler (tags are optional)
        $handler = new CloudWatch($client, $groupName, $streamName, $retentionDays, 10000, $tags, $logLevel);

        $formatter = new \Monolog\Formatter\JsonFormatter();

        $handler->setFormatter($formatter);

        // Create a log channel
        $logger = new Logger($name);

        // Set handler
        $logger->pushHandler($handler);

        return $logger;
    }
}
