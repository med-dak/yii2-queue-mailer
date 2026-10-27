<?php

namespace Jbboisseau\Yii2\QueueMailer\Tests;

use Jbboisseau\Yii2\QueueMailer\Tests\Support\QueueMailerTestCase;
use yii\mail\MailerInterface;

class MailerTest extends QueueMailerTestCase
{
    public function testInterface(): void
    {
        self::assertInstanceOf(MailerInterface::class, $this->mailer);
    }

    public function testSend(): void
    {
        self::assertTrue($this->mailer->send($this->createMessage()));
        self::assertCount(1, $this->queue->jobs);
    }

    public function testSendMultiple(): void
    {
        self::assertSame(2, $this->mailer->sendMultiple([
            $this->createMessage('one'),
            $this->createMessage('two'),
        ]));
        self::assertCount(1, $this->queue->jobs);
    }

    public function testGetLastJobId(): void
    {
        $this->mailer->send($this->createMessage());

        self::assertSame(1, $this->mailer->getLastJobId());
    }
}
