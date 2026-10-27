<?php

namespace Jbboisseau\Yii2\QueueMailer\Tests\Support;

use Jbboisseau\Yii2\QueueMailer\Mailer;
use PHPUnit\Framework\TestCase;
use yii\helpers\FileHelper;
use yii\mail\MessageInterface;
use yii\symfonymailer\Mailer as SymfonyMailer;

abstract class QueueMailerTestCase extends TestCase
{
    protected FakeQueue $queue;
    protected Mailer $mailer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queue = new FakeQueue();
        $this->mailer = \Yii::createObject([
            'class' => Mailer::class,
            'queue' => $this->queue,
            'syncMailer' => [
                'class' => SymfonyMailer::class,
                'useFileTransport' => true,
                'fileTransportPath' => '@runtime/mail',
            ],
        ]);
    }

    protected function tearDown(): void
    {
        $mailPath = \Yii::getAlias('@runtime/mail');
        if (is_dir($mailPath)) {
            FileHelper::removeDirectory($mailPath);
        }

        parent::tearDown();
    }

    protected function createMessage(string $body = 'test message'): MessageInterface
    {
        return $this->mailer->compose()
            ->setTo('test@example.org')
            ->setFrom('no-reply@example.org')
            ->setHtmlBody($body);
    }
}
