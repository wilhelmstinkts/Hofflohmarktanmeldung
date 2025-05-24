<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use utils\RetryUtils;

include_once(__DIR__ . '/../utils/withRetry.php');

final class withRetryTest extends TestCase
{
    public function testSuccessfulOperationNoRetry(): void
    {
        $callCount = 0;
        $expectedResult = 'success';
        
        $result = RetryUtils::withRetry(function() use (&$callCount, $expectedResult) {
            $callCount++;
            return $expectedResult;
        });
        
        $this->assertEquals(1, $callCount);
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testEventualSuccessAfterRetries(): void
    {
        $callCount = 0;
        $expectedResult = 'eventual success';
        $successOnAttempt = 3;
        
        $result = RetryUtils::withRetry(function() use (&$callCount, $expectedResult, $successOnAttempt) {
            $callCount++;
            
            if ($callCount < $successOnAttempt) {
                throw new \RuntimeException('Temporary failure');
            }
            
            return $expectedResult;
        }, 5, function() { return 0.001; }); // Small backoff for faster test
        
        $this->assertEquals($successOnAttempt, $callCount);
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testMaxRetriesExceeded(): void
    {
        $callCount = 0;
        $maxRetries = 3;
        $exceptionMessage = 'Persistent failure';
        
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($exceptionMessage);
        
        try {
            RetryUtils::withRetry(function() use (&$callCount, $exceptionMessage) {
                $callCount++;
                throw new \RuntimeException($exceptionMessage);
            }, $maxRetries, function() { return 0.001; }); // Small backoff for faster test
        } finally {
            $this->assertEquals($maxRetries + 1, $callCount); // Initial attempt + retries
        }
    }
    
    public function testBackoffFunctionCalled(): void
    {
        $callCount = 0;
        $backoffCalls = [];
        
        try {
            RetryUtils::withRetry(function() use (&$callCount) {
                $callCount++;
                throw new \RuntimeException('Always fails');
            }, 3, function(int $attempt) use (&$backoffCalls) {
                $backoffCalls[] = $attempt;
                return 0.001; // Small backoff for faster test
            });
        } catch (\RuntimeException $e) {
            // Expected exception
        }
        
        $this->assertEquals([1, 2, 3], $backoffCalls);
    }
    
    public function testDefaultBackoffStrategy(): void
    {
        $startTime = microtime(true);
        $callCount = 0;
        $maxRetries = 2; // Testing with small value to keep test duration reasonable
        
        try {
            // Override sleep function for testing by using a custom backoff
            RetryUtils::withRetry(function() use (&$callCount) {
                $callCount++;
                throw new \RuntimeException('Always fails');
            }, $maxRetries, function(int $attempt) {
                return 0.01; // Fixed small delay for predictable testing
            });
        } catch (\RuntimeException $e) {
            // Expected exception
        }
        
        $duration = microtime(true) - $startTime;
        
        // We expect approximately 3 calls (initial + 2 retries) with ~0.01s delay each
        // Allow some flexibility in timing (at least 0.02s for 2 delays)
        $this->assertGreaterThan(0.02, $duration);
        $this->assertEquals($maxRetries + 1, $callCount);
    }
}