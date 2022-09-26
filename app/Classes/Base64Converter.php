<?php

namespace App\Classes;

Class Base64Converter
{
    /**
     * @var string
     */
    private string $base64;

    /**
     * @var string
     */
    private string $data;

    /**
     * @var string
     */
    private string $extension;

    public function __construct(string $base64)
    {
        $this->base64    = $base64;
        $this->extension = $this->getExtension();
        $this->data      = $this->getData();
    }

    /**
     * @return bool|string
     */
    public function getDecodedData(): bool|string
    {
        return base64_decode($this->getData());
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        $result = $this->data;

        if (is_null($result)) {

            $replace = substr($this->base64, 0, strpos($this->base64, ',') + 1);

            $result = $this->data = str_replace(' ', '+', str_replace($replace, '', $this->base64));
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        $result = $this->extension;

        if (is_null($result)) {
            $result = explode('/', explode(':', substr($this->base64, 0, strpos($this->base64, ';')))[1])[1];
        }

        return $result;
    }
}
