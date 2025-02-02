<?php

namespace AssociativeAssertions;

use AssociativeAssertions\Constraint\DateTimeStr;
use AssociativeAssertions\Constraint\Digit;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\TestCase;


abstract class AbstractAssociativeArray extends TestCase
{
    /**
     * @param array  $expected
     * @param array  $actual
     * @param string $message
     */
    public static function assertAssociativeArray($expected, $actual, $message = '')
    {
        if (!\is_array($expected)) {
            throw InvalidArgumentException::create(1, 'array');
        }

        if (!\is_array($actual)) {
            throw InvalidArgumentException::create(2, 'array');
        }

        self::recursiveAssociativeAssertion($expected, $actual, $message);
    }


    /**
     * @param array  $expected
     * @param array  $actual
     * @param string $message
     */
    private static function recursiveAssociativeAssertion($expected, $actual, $message = '')
    {
        self::assertEqualsCanonicalizing(array_keys($expected), array_keys($actual), $message);
        foreach ($expected as $key => $value) {
            if (\is_array($value)) {
                self::recursiveAssociativeAssertion($expected[$key], $actual[$key], $message);
            } else if (is_callable($value)) {
                $value($actual[$key], $message);
            } else {
                self::assertSame($value, $actual[$key], $message);
            }
        }
    }


    /**
     * @param string $format
     * @param string $actual
     * @param string $message
     */
    public static function assertDateTimeStr($format, $actual, $message = '')
    {
        if (!\is_string($format)) {
            throw InvalidArgumentException::create(1, 'string');
        }

        if (!\is_string($actual)) {
            throw InvalidArgumentException::create(2, 'string');
        }

        $constraint = new DateTimeStr($format);
        self::assertThat($actual, $constraint, $message);
    }


    /**
     * @param string $actual
     * @param string $message
     */
    public static function assertDigit($actual, $message = '')
    {
        $constraint = new Digit();
        self::assertThat($actual, $constraint, $message);
    }
}
