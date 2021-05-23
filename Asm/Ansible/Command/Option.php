<?php

declare(strict_types=1);

namespace Asm\Ansible\Command;

/**
 * Class Option
 *
 * @package Asm\Ansible\Command
 * @author  Metagûsto <opensource@metagusto.com>
 */
class Option
{
    protected string|null $name = null;
    protected string|null $value = null;

    /**
     * Option constructor.
     * @param string|null $name
     * @param string|null $value
     */
    public function __construct(?string $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s=%s', $this->name, $this->value);
    }

    /**
     * Converts the option to string
     * @return string
     */
    public function toString(): string
    {
        return $this->__toString();
    }

    /**
     * @param Option|null $other
     * @return bool
     */
    public function equals(?Option $other)
    {
        if ($other === null) {
            return false;
        }

        return $this->name === $other->getName() && $this->value === $other->value;
    }
}
