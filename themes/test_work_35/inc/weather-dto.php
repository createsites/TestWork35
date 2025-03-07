<?php

class WeatherDTO {
    private $temperature;
    private $external_id;

    public function __construct($temperature, $externalId = 0) {
        $this->temperature = $temperature;
        $this->external_id = $externalId;
    }

    // Getters
    public function getTemperature() {
        return $this->temperature;
    }

    public function getExternalId(): mixed
    {
        return $this->external_id;
    }

    // Setters
    public function setTemperature($temperature) {
        $this->temperature = $temperature;
    }

    public function setExternalId(mixed $external_id): void
    {
        $this->external_id = $external_id;
    }
}
