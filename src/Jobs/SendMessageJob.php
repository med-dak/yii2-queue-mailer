<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace Jbboisseau\Yii2\QueueMailer\Jobs;

use Jbboisseau\Yii2\QueueMailer\JobInterface;
use Jbboisseau\Yii2\QueueMailer\Mailer;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\mail\MessageInterface;

class SendMessageJob extends BaseObject implements JobInterface
{
    /** @var MessageInterface */
    public $message;
    /** @var string|Mailer */
    public $mailer;

    /**
     * @inheritdoc
     * @see JobInterface::execute()
     *
     * @throws InvalidConfigException
     * @throws \InvalidArgumentException
     */
    public function execute($queue)
    {
        if (!$this->message instanceof MessageInterface) {
            throw new \InvalidArgumentException('Message must be an instance of ' . MessageInterface::class);
        }
        /** @var Mailer $mailer */
        $mailer = Instance::ensure($this->mailer, Mailer::class);
        return $mailer->getSyncMailer()->send($this->message);
    }
}
