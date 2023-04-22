<?php

declare(strict_types=1);

namespace Asm\Ansible\Utils;

use PHPUnit\Framework\TestCase;

/**
 * @group utils
 */
class StrTest extends TestCase
{
    /**
     * Assert valid JSON string is correctly checked.
     *
     * @return void
     */
    public function testJsonWithValidFormat(): void
    {
        $value = '{ "key1": "value1" }';

        $this->assertTrue(Str::isJsonFormatted($value));
    }

    /**
     * Assert string is not valid JSON.
     *
     * @return void
     */
    public function testStringIsNotJson(): void
    {
        $value = 'something';

        $this->assertFalse(Str::isJsonFormatted($value));
    }

    /**
     * Assert single quotes are properly escaped.
     *
     * @return void
     */
    public function testSingleQuotesEscaped(): void
    {
        $value = "O'What";

        $this->assertEquals("O\'What", Str::escapeSingleQuotes($value));
    }
}
