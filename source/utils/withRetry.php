<?php

namespace utils;


class RetryUtils
{
    /**
     * Executes a function with automatic retry logic
     *
     * @param callable $fn The function to execute
     * @param int $maxRetries Maximum number of retry attempts
     * @param callable $backoffFn Function that calculates wait time (in seconds) based on retry attempt number
     * @return mixed Return value of the executed function
     * @throws Throwable The final exception if all retries fail
     */
    public static function withRetry(callable $fn, int $maxRetries = 3, callable | null $backoffFn = null): mixed
    {
        // Default backoff strategy: exponential backoff (2^n seconds)
        $backoffFn = $backoffFn ?? function (int $attempt) {
            return pow(2, $attempt);
        };

        $attempt = 0;
        $lastException = null;

        while ($attempt <= $maxRetries) {
            try {
                return $fn();
            } catch (\Throwable $e) {
                $lastException = $e;
                $attempt++;

                if ($attempt > $maxRetries) {
                    break;
                }

                $backoffTime = $backoffFn($attempt);
                usleep($backoffTime * 1000000); // Convert seconds to microseconds
            }
        }

        // If we get here, all retries failed
        throw $lastException;
    }
}
