<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testInstance(): void
    {
        $option = new Option('name', 'value');
        $this->assertEquals('name', $option->getName());
        $this->assertEquals('value', $option->getValue());

        $this->assertTrue($option->equals(new Option('name', 'value')));
        $this->assertFalse($option->equals(null));
        $this->assertEquals('name=value', $option->toString());
        $this->assertEquals('name=value', (string)$option);

        $option->setName('_name');
        $option->setValue('_value');
        $this->assertEquals('_name', $option->getName());
        $this->assertEquals('_value', $option->getValue());
    }


}
