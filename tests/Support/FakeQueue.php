<?php

namespace Jbboisseau\Yii2\QueueMailer\Tests\Support;

/**
 * Minimal queue used by the test suite.
 *
 * The library only requires an object exposing push(), so the tests do not
 * need yiisoft/yii2-queue (which has no Yii 2.2 compatible release yet).
 */
class FakeQueue
{
    /** @var array<int, mixed> */
    public array $jobs = [];

    public function push($job): int
    {
        $this->jobs[] = $job;

        return count($this->jobs);
    }
}
