<?php

namespace Jbboisseau\Yii2\QueueMailer;

/**
 * Queue job contract.
 *
 * When yiisoft/yii2-queue is installed, this interface extends its own
 * JobInterface so the jobs can be pushed to a yii2-queue backend.
 * Otherwise it stays a lightweight local contract, allowing this package
 * to be installed with Yii 2.2 even though yii2-queue 2.x still targets
 * Yii 2.0.
 */
if (interface_exists(\yii\queue\JobInterface::class)) {
    interface JobInterface extends \yii\queue\JobInterface
    {
    }
} else {
    interface JobInterface
    {
        /**
         * @param mixed $queue
         * @return mixed
         */
        public function execute($queue);
    }
}
