# Queue mailer decorator for Yii2 framework

Send your emails in the background using a queue.

This fork targets **Yii 2.2** (`yiisoft/yii2 22.0.x-dev`) and PHP 8.3+.

## Requirements

- PHP >= 8.3
- `yiisoft/yii2` 2.2 (`22.0.x-dev`)
- A queue object exposing `push($job)`
- A synchronous mailer implementing `yii\mail\MailerInterface`
  (for example `yiisoft/yii2-symfonymailer`)

> **About yii2-queue:** the upstream `yiisoft/yii2-queue` 2.x still requires
> `yiisoft/yii2 ~2.0.54` and has no Yii 2.2 release yet. This package no longer
> requires it at runtime: any queue exposing `push()` works. If
> `yiisoft/yii2-queue` is installed (for example a fork relaxed for Yii 2.2),
> the jobs automatically implement its `yii\queue\JobInterface`.

## Installation

The preferred way is through Composer. Because this is a fork, declare its VCS
repository first:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/med-dak/yii2-queue-mailer.git"
        }
    ],
    "require": {
        "jbboisseau/yii2-queue-mailer": "dev-master"
    }
}
```

Then run `composer update jbboisseau/yii2-queue-mailer`.

## Usage

Configure the queue and make the queue mailer the application's `mailer`
component. The `syncMailer` is the real mailer used by the worker to send the
message:

```php
'mailer' => [
    'class' => \Jbboisseau\Yii2\QueueMailer\Mailer::class,
    'queue' => 'queue', // component ID, object, or callable returning the queue
    'syncMailer' => [
        'class' => \yii\symfonymailer\Mailer::class,
        'useFileTransport' => false,
        // 'transport' => [...],
    ],
],
```

Then send emails as usual:

```php
$message = \Yii::$app->mailer->compose()
    ->setSubject('test subject')
    ->setFrom('test@example.org')
    ->setHtmlBody('test body')
    ->setTo('user@example.org');

\Yii::$app->mailer->send($message);
```

`send()` returns `true` once the job has been pushed, not once the email has
actually been delivered. Use `getLastJobId()` to retrieve the queue job ID:

```php
$jobId = \Yii::$app->mailer->getLastJobId();
```

The worker must run the queue (for example `yii queue/listen` with a
Yii 2.2-compatible queue implementation) so that the `SendMessageJob` /
`SendMultipleMessagesJob` jobs execute and call the synchronous mailer.

## Tests

```bash
composer install
vendor/bin/phpunit
```

## License

MIT

## Links

- Fork: https://github.com/med-dak/yii2-queue-mailer
- Original: https://github.com/jbboisseau/yii2-queue-mailer
