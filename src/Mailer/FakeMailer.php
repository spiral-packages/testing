<?php

declare(strict_types=1);

namespace Spiral\Testing\Mailer;

use Closure;
use PHPUnit\Framework\TestCase;
use Spiral\Mailer\MailerInterface;
use Spiral\Mailer\MessageInterface;

class FakeMailer implements MailerInterface
{
    private array $messages = [];

    private function filterMessages(string $type, Closure $callback = null): array
    {
        $messages = \array_filter($this->messages, static function (MessageInterface $msg) use ($type): bool {
            return $msg instanceof $type;
        });

        $callback = $callback ?: static function (): bool {
            return true;
        };

        return \array_filter($messages, static function (MessageInterface $msg) use ($callback) {
            return $callback($msg);
        });
    }

    public function assertSent(string $message, Closure $callback = null): void
    {
        $messages = $this->filterMessages($message, $callback);

        TestCase::assertTrue(
            \count($messages) > 0,
            \sprintf('The expected [%s] message was not sent.', $message)
        );
    }

    public function assertNotSent(string $message, Closure $callback = null): void
    {
        $messages = $this->filterMessages($message, $callback);

        TestCase::assertCount(
            0,
            $messages,
            \sprintf('The unexpected [%s] message was sent.', $message)
        );
    }

    public function assertSentTimes(string $message, int $times = 1): void
    {
        $messages = $this->filterMessages($message);

        TestCase::assertCount(
            $times,
            $messages,
            \sprintf(
                'The expected [%s] message was sent {%d} times instead of {%d} times.',
                $message,
                \count($messages),
                $times
            )
        );
    }

    public function assertNothingSent(): void
    {
        $messages = \array_map(static function (MessageInterface $message): string {
            return $message::class;
        }, $this->messages);

        $messages = \implode(', ', $messages);

        TestCase::assertCount(
            0,
            $this->messages,
            \sprintf(
                'The following messages were sent unexpectedly: %s.',
                $messages
            )
        );
    }

    public function send(MessageInterface ...$messages): void
    {
        foreach ($messages as $message) {
            $this->messages[] = $message;
        }
    }
}
