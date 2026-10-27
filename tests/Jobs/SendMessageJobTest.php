<?php

namespace Jbboisseau\Yii2\QueueMailer\Tests\Jobs;

use Jbboisseau\Yii2\QueueMailer\Jobs\SendMessageJob;
use Jbboisseau\Yii2\QueueMailer\Tests\Support\QueueMailerTestCase;

class SendMessageJobTest extends QueueMailerTestCase
{
    public function testExecute(): void
    {
        $job = \Yii::createObject([
            'class' => SendMessageJob::class,
            'mailer' => $this->mailer,
            'message' => $this->createMessage(),
        ]);

        self::assertTrue($job->execute($this->queue));
    }
}
