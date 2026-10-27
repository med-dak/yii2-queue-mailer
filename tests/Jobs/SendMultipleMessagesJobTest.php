<?php

namespace Jbboisseau\Yii2\QueueMailer\Tests\Jobs;

use Jbboisseau\Yii2\QueueMailer\Jobs\SendMultipleMessagesJob;
use Jbboisseau\Yii2\QueueMailer\Tests\Support\QueueMailerTestCase;

class SendMultipleMessagesJobTest extends QueueMailerTestCase
{
    public function testExecute(): void
    {
        $job = \Yii::createObject([
            'class' => SendMultipleMessagesJob::class,
            'mailer' => $this->mailer,
            'messages' => [
                $this->createMessage('one'),
                $this->createMessage('two'),
            ],
        ]);

        self::assertSame(2, $job->execute($this->queue));
    }
}
