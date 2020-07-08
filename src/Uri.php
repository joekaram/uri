<?php

namespace Hydra\Uri;

use Exception;

class Uri
{
    private string $raw;
    private ?string $scheme;
    private ?string $host;
    private ?int $port;
    private ?string $path;
    private array $query;
    private ?string $fragment;

    private function __construct(string $uri)
    {
        $trimmed = trim($uri);

        if (empty($trimmed)) {
            throw new Exception("The URI provided is empty.");
        }

        $this->raw = $uri;

        $this->parseUri($trimmed);
    }

    private function parseUri(string $uri): Uri
    {
        $parsed = parse_url($uri);

        if (!$parsed) {
            throw new Exception("Could not parse URI.");
        }

        $this->setAttributes($parsed);

        return $this;
    }

    private function setAttributes(array $parsed)
    {
        $this->scheme = array_key_exists("scheme", $parsed)
            ? $parsed["scheme"]
            : null;

        $this->host = array_key_exists("host", $parsed)
            ? $parsed["host"]
            : null;

        $this->port = array_key_exists("port", $parsed)
            ? $parsed["port"]
            : null;

        $this->path = array_key_exists("path", $parsed)
            ? $parsed["path"]
            : null;

        $this->query = array_key_exists("query", $parsed)
            ? $this->formatQueryArray($this->queryToArray($parsed["query"]))
            : [];

        $this->fragment = array_key_exists("fragment", $parsed)
            ? $parsed["fragment"]
            : null;
    }

    private function queryToArray(string $query): array
    {
        return array_filter(explode("&", $query), fn($q) => !empty($q));
    }

    private function formatQueryArray(array $query): array
    {
        $return = [];

        foreach($query as $q) {
            $arr = explode("=", $q);
            if (count($arr) == 0) {
                continue;
            }
            if (count($arr) == 1) {
                $return[$arr[0]] = null;
                continue;
            }
            $return[$arr[0]] = !empty($arr[1]) ? $arr[1] : null;
        }

        return $return;
    }

    public static function parse(string $uri): Uri
    {
        return new static($uri);
    }

    public function scheme(): ?string
    {
        return $this->scheme;
    }

    public function host(): ?string
    {
        return $this->host;
    }

    public function port(): ?int
    {
        return $this->port;
    }

    public function path(): ?string
    {
        return $this->path;
    }

    public function queries(): array
    {
        return $this->query ?? [];
    }

    public function queryHas(string $key): bool
    {
        return array_key_exists($key, $this->query);
    }

    public function query(string $key)
    {
        if (!$this->queryHas($key)) {
            return null;
        }

        return $this->query[$key];
    }

    public function fragment(): ?string
    {
        return $this->fragment;
    }
}
