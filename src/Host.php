<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser;

/**
 * Class Host
 * @package NorthernLights\HostsFileParser
 */
class Host
{
    /** @var int */
    protected $line;

    /** @var string */
    protected $ip;

    /** @var array */
    protected $domains;

    /**
     * HostEntry constructor.
     * @param string $ip
     * @param array $domains
     * @param int $line
     */
    public function __construct(string $ip, array $domains, int $line)
    {
        $this->ip = $ip;
        $this->domains = $domains;
        $this->line = $line;
    }

    /**
     * Get original entry
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getEntry();
    }

    /**
     * Collection of data
     *
     * @return array
     */
    public function getContext(): array
    {
        return [
            'ip' => $this->ip,
            'domains' => $this->domains,
            'line' => $this->line
        ];
    }

    public function getEntry(): string
    {
        return sprintf(
            '%s       %s',
            $this->ip,
            implode(' ', $this->domains)
        );
    }

    /**
     * IP address
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Associated domains
     *
     * @return array
     */
    public function getDomains(): array
    {
        return $this->domains;
    }

    /**
     * Line in file where entry was located
     *
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }
}
