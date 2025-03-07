<?php

require get_stylesheet_directory() . '/inc/weather-dto.php';

// Получение погоды по API сервиса openweathermap.org
class OpenWeather
{
    const API_KEY = 'f829158fb67b4bf524f9ec0a3f9ef290';

    /**
     * Получить по геопозиции
     * @param $latitude string
     * @param $longitude string
     * @return WeatherDTO для вывода
     * @throws Exception
     */
    public function getByGeo($latitude, $longitude)
    {
        $weather_api_url = 'https://api.openweathermap.org/data/2.5/weather?lat=' . $latitude
            . '&lon=' . $longitude
            . '&appid=' . self::API_KEY
            . '&units=metric';

        $weather_data = $this->makeRequest($weather_api_url);

        // Проверяем, что в ответе есть данные о температуре
        if (!isset($weather_data->main->temp)) {
            throw new \Exception('Temperature data is unavailable.');
        }
        // и об id
        if (!isset($weather_data->id)) {
            throw new \Exception('ID is unavailable.');
        }

        return new WeatherDTO($weather_data->main->temp, $weather_data->id);
    }

    private function makeRequest($api_url)
    {
        $response = wp_remote_get($api_url);
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            throw new \Exception("Something went wrong: $error_message");
        }

        // Получаем HTTP статус
        $http_status = wp_remote_retrieve_response_code($response);
        // Получаем тело ответа
        $body = wp_remote_retrieve_body($response);
        $weather_data = json_decode($body);

        // Обработка HTTP статуса отличного от 200
        if ($http_status !== 200) {
            $error = match ($http_status) {
                400 => 'Bad request',
                default => 'There is an error',
            };
            $message = isset($weather_data->message) ? ': ' . $weather_data->message : '';
            throw new \Exception($error . $message);
        }

        return $weather_data;
    }

    public function getByIds($cities_id)
    {
        $weather_api_url = 'https://api.openweathermap.org/data/2.5/group?id=' . join(',', $cities_id)
            . '&appid=' . self::API_KEY
            . '&units=metric';

        $weather_data = $this->makeRequest($weather_api_url);

        // Проверяем, что в ответе есть данные
        if (!isset($weather_data->list)) {
            throw new \Exception('Data is unavailable.');
        }

        foreach ($weather_data->list as $city) {
            if (($key = array_search($city->id, $cities_id)) !== false) {
                unset($cities_id[$key]);
                $cities_id[$key] = new WeatherDTO(
                    $city->main->temp,
                    $city->id
                );
            }
        }

        return $cities_id;
    }
}
