<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */
namespace Jbboisseau\Yii2\QueueMailer;

use Jbboisseau\Yii2\QueueMailer\Jobs\SendMultipleMessagesJob;
use Jbboisseau\Yii2\QueueMailer\Jobs\SendMessageJob;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yii\mail\BaseMailer;

class Mailer extends BaseMailer implements MailerInterface
{
    /** @var string */
    public $id = 'mailer';
    /** @var object|string|callable Queue implementation exposing a push() method */
    protected $queue = 'queue';
    /** @var MailerInterface|string|callable */
    protected $syncMailer;
    /** @var int|string|null */
    protected $lastJobId;

    /**
     * @return object
     * @throws InvalidConfigException
     */
    public function getQueue()
    {
        if (is_string($this->queue)) {
            $this->queue = \Yii::$app->get($this->queue);
        } elseif (is_callable($this->queue)) {
            $this->queue = call_user_func($this->queue);
        }
        if (!is_object($this->queue) || !method_exists($this->queue, 'push')) {
            throw new InvalidConfigException('Queue must be an object exposing a push() method.');
        }
        return $this->queue;
    }

    /**
     * @param mixed $queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

    /**
     * @return MailerInterface
     * @throws InvalidConfigException
     */
    public function getSyncMailer()
    {
        if (is_callable($this->syncMailer)) {
            $this->syncMailer = call_user_func($this->syncMailer);
        }
        return $this->syncMailer = Instance::ensure($this->syncMailer, MailerInterface::class);
    }

    /**
     * @param mixed $syncMailer
     */
    public function setSyncMailer($syncMailer)
    {
        $this->syncMailer = $syncMailer;
    }

    /**
     * @inheritdoc
     * @see MailerInterface::compose()
     */
    public function compose($view = null, array $params = [])
    {
        return $this->getSyncMailer()->compose($view, $params);
    }

    /**
     * Dummy implementation required by BaseMailer.
     * Messages are actually sent by SendMessageJob / SendMultipleMessagesJob.
     */
    protected function sendMessage($message)
    {
        return true;
    }

    /**
     * @inheritdoc
     * @see MailerInterface::send()
     *
     * @throws InvalidConfigException
     */
    public function send($message)
    {
        $job = \Yii::createObject(SendMessageJob::class);
        $job->mailer = $this->id;
        $job->message = $message;
        $this->lastJobId = $this->getQueue()->push($job);
        return $this->lastJobId !== null;
    }

    /**
     * @inheritdoc
     * @see MailerInterface::sendMultiple()
     *
     * @throws InvalidConfigException
     */
    public function sendMultiple(array $messages)
    {
        $job = \Yii::createObject(SendMultipleMessagesJob::class);
        $job->mailer = $this->id;
        $job->messages = $messages;
        $this->lastJobId = $this->getQueue()->push($job);
        return $this->lastJobId !== null ? count($messages) : 0;
    }

    /**
     * @return int|string|null
     */
    public function getLastJobId()
    {
        return $this->lastJobId;
    }
}
