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

class SendMultipleMessagesJob extends BaseObject implements JobInterface
{
    /** @var MessageInterface[] */
    public $messages = [];
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
        if (!is_array($this->messages)) {
            throw new \InvalidArgumentException('Messages must be an array of ' . MessageInterface::class);
        }
        /** @var Mailer $mailer */
        $mailer = Instance::ensure($this->mailer, Mailer::class);
        return $mailer->getSyncMailer()->sendMultiple($this->messages);
    }
}
