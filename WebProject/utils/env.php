<?php


class DotEnv
{
    protected $path;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load()
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s is not readable', $this->path));
        }

        $envVars = parse_ini_file($this->path, false, INI_SCANNER_RAW);

        foreach ($envVars as $name => $value) {
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV) && !array_key_exists($name, $_SERVER)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
            }
        }
    }
}
