<?php

namespace Jooservices\XcrawlerClient\Settings;

use Jooservices\XcrawlerClient\Interfaces\SettingsContainerInterface;
use ReflectionProperty;

abstract class AbstractSettingsContainer implements SettingsContainerInterface
{
    /**
     * SettingsContainerAbstract constructor.
     *
     * @param iterable|null $properties
     */
    public function __construct(iterable $properties = null)
    {
        if (!empty($properties)) {
            $this->fromIterable($properties);
        }
    }

    public function __get(string $property)
    {
        if (!property_exists($this, $property) || $this->isPrivate($property)) {
            return null;
        }

        $method = 'get' . $property;

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        return $this->{$property};
    }

    public function __set(string $property, $value): void
    {
//        if (!property_exists($this, $property) || $this->isPrivate($property)) {
//            return;
//        }

        $method = 'set' . $property;

        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], [$value]);

            return;
        }

        $this->{$property} = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->{$property}) && !$this->isPrivate($property);
    }

    protected function isPrivate(string $property): bool
    {
        static $properties;

        if (!isset($properties[$property])) {
            $properties[$property] = new ReflectionProperty($this, $property);
        }
        return $properties[$property]->isPrivate();
    }

    public function __unset(string $property): void
    {

        if ($this->__isset($property)) {
            unset($this->{$property});
        }

    }

    public function __toString(): string
    {
        return $this->toJSON();
    }

    public function fromIterable(iterable $properties): SettingsContainerInterface
    {
        foreach ($properties as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toJSON(int $jsonOptions = null): string
    {
        return json_encode($this, $jsonOptions ?? 0);
    }

    public function fromJSON(string $json): SettingsContainerInterface
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this->fromIterable($data);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
