<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        // -----------------------------------------
        // STEP 1: Get city / coordinates
        // -----------------------------------------

        $city = trim(request('city', 'Kolkata'));

        $latitude = request('latitude');
        $longitude = request('longitude');

        $country = '';

        // Default timezone
        $timezone = 'UTC';

        // -----------------------------------------
        // If user selected "Use My Location"
        // -----------------------------------------

        if ($latitude !== null && $longitude !== null) {

            $latitude = (float) $latitude;
            $longitude = (float) $longitude;

            // -----------------------------------------
            // Reverse Geocoding
            // Coordinates → City + Country
            // -----------------------------------------

            $reverseGeocodeResponse = Http::withHeaders([
                'User-Agent' => 'Laravel Weather App/1.0',
            ])->get(
                'https://nominatim.openstreetmap.org/reverse',
                [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'format' => 'jsonv2',
                    'zoom' => 10,
                    'addressdetails' => 1,
                ]
            );

            if ($reverseGeocodeResponse->successful()) {

                $reverseData = $reverseGeocodeResponse->json();

                $address = $reverseData['address'] ?? [];

                // Try city
                $city = $address['city']
                    ?? $address['town']
                    ?? $address['municipality']
                    ?? $address['village']
                    ?? $city;

                // Country
                $country = $address['country'] ?? '';
            }
        }

        // -----------------------------------------
        // City Search using Open-Meteo Geocoding
        // -----------------------------------------

        else {

            $geocodingResponse = Http::get(
                'https://geocoding-api.open-meteo.com/v1/search',
                [
                    'name' => $city,
                    'count' => 1,
                    'language' => 'en',
                    'format' => 'json',
                ]
            );

            $geocodingData = $geocodingResponse->json();

            // -----------------------------------------
            // Check if city exists
            // -----------------------------------------

            if (
                !$geocodingResponse->successful() ||
                empty($geocodingData['results'])
            ) {
                return back()->with(
                    'error',
                    'City not found. Please try another city.'
                );
            }

            // -----------------------------------------
            // Get first location result
            // -----------------------------------------

            $location = $geocodingData['results'][0];

            $latitude = $location['latitude'];
            $longitude = $location['longitude'];

            $city = $location['name'] ?? $city;

            $country = $location['country'] ?? '';

            // -----------------------------------------
            // Get timezone from Geocoding result
            // -----------------------------------------

            $timezone = $location['timezone'] ?? $timezone;
        }

        // -----------------------------------------
        // STEP 2: Google Weather API
        // -----------------------------------------

        $apiKey = env('GOOGLE_WEATHER_API_KEY');

        $response = Http::get(
            'https://weather.googleapis.com/v1/currentConditions:lookup',
            [
                'key' => $apiKey,
                'location.latitude' => $latitude,
                'location.longitude' => $longitude,
            ]
        );

        // -----------------------------------------
        // STEP 3: Google Weather API SUCCESS
        // -----------------------------------------

        if ($response->successful()) {

            $weather = $response->json();

            // Temperature
            $temperature =
                $weather['temperature']['degrees'] ?? 0;

            // Feels like temperature
            $feelsLike =
                $weather['feelsLikeTemperature']['degrees']
                ?? $temperature;

            // Weather condition
            $condition =
                $weather['weatherCondition']['description']['text']
                ?? 'Unknown';

            // Humidity
            $humidity =
                $weather['relativeHumidity'] ?? 0;

            // Wind speed
            $wind =
                $weather['wind']['speed']['value'] ?? 0;

            // UV Index
            $uvIndex =
                $weather['uvIndex'] ?? 0;

            // Google weather type
            $weatherType =
                $weather['weatherCondition']['type'] ?? '';

            // -----------------------------------------
            // Convert Google weather condition to icon
            // -----------------------------------------

            $weatherIcon = match (true) {

                str_contains($weatherType, 'THUNDERSTORM')
                    => '⛈️',

                str_contains($weatherType, 'RAIN')
                    => '🌧️',

                str_contains($weatherType, 'DRIZZLE')
                    => '🌦️',

                str_contains($weatherType, 'SNOW')
                    => '❄️',

                str_contains($weatherType, 'FOG')
                    => '🌫️',

                str_contains($weatherType, 'CLOUD')
                    => '☁️',

                default
                    => '☀️',
            };
        }

        // -----------------------------------------
        // STEP 4: Google API FAILED
        // Open-Meteo fallback
        // -----------------------------------------

        else {

            $fallbackResponse = Http::get(
                'https://api.open-meteo.com/v1/forecast',
                [
                    'latitude' => $latitude,
                    'longitude' => $longitude,

                    'current' =>
                        'temperature_2m,relative_humidity_2m,apparent_temperature,wind_speed_10m,weather_code',

                    'timezone' => 'auto',
                ]
            );

            // -----------------------------------------
            // Check fallback API
            // -----------------------------------------

            if (!$fallbackResponse->successful()) {

                return back()->with(
                    'error',
                    'Weather information is not available for this location.'
                );
            }

            $fallbackWeather = $fallbackResponse->json();

            // -----------------------------------------
            // Get timezone from fallback API
            // -----------------------------------------

            $timezone =
                $fallbackWeather['timezone'] ?? $timezone;

            // -----------------------------------------
            // Get fallback weather data
            // -----------------------------------------

            $temperature =
                $fallbackWeather['current']['temperature_2m']
                ?? 0;

            $feelsLike =
                $fallbackWeather['current']['apparent_temperature']
                ?? $temperature;

            $humidity =
                $fallbackWeather['current']['relative_humidity_2m']
                ?? 0;

            $wind =
                $fallbackWeather['current']['wind_speed_10m']
                ?? 0;

            $weatherCode =
                $fallbackWeather['current']['weather_code']
                ?? 0;

            // -----------------------------------------
            // Weather code → Description
            // -----------------------------------------

            $condition = match ($weatherCode) {

                0
                    => 'Clear sky',

                1, 2, 3
                    => 'Partly cloudy',

                45, 48
                    => 'Fog',

                51, 53, 55
                    => 'Drizzle',

                56, 57
                    => 'Freezing drizzle',

                61, 63, 65
                    => 'Rain',

                66, 67
                    => 'Freezing rain',

                71, 73, 75, 77
                    => 'Snow',

                80, 81, 82
                    => 'Rain showers',

                85, 86
                    => 'Snow showers',

                95
                    => 'Thunderstorm',

                96, 99
                    => 'Thunderstorm with hail',

                default
                    => 'Unknown',
            };

            // -----------------------------------------
            // Weather code → Icon
            // -----------------------------------------

            $weatherIcon = match ($weatherCode) {

                0
                    => '☀️',

                1, 2, 3
                    => '🌤️',

                45, 48
                    => '🌫️',

                51, 53, 55, 56, 57
                    => '🌦️',

                61, 63, 65, 66, 67, 80, 81, 82
                    => '🌧️',

                71, 73, 75, 77, 85, 86
                    => '❄️',

                95, 96, 99
                    => '⛈️',

                default
                    => '🌤️',
            };

            // Open-Meteo current request
            // does not include UV
            $uvIndex = 0;
        }

        // -----------------------------------------
        // STEP 5: 5-Day Weather Forecast
        // -----------------------------------------

        $forecastResponse = Http::get(
            'https://api.open-meteo.com/v1/forecast',
            [
                'latitude' => $latitude,
                'longitude' => $longitude,

                'daily' =>
                    'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max,sunrise,sunset',

                'timezone' => 'auto',

                'forecast_days' => 5,
            ]
        );

        // -----------------------------------------
        // Default Sunrise / Sunset
        // -----------------------------------------

        $sunrise = null;
        $sunset = null;

        // -----------------------------------------
        // Default empty forecast
        // -----------------------------------------

        $forecast = [];

        if ($forecastResponse->successful()) {

            $forecastData = $forecastResponse->json();

            // -----------------------------------------
            // Get location timezone
            // -----------------------------------------

            $timezone =
                $forecastData['timezone'] ?? $timezone;

            // -----------------------------------------
            // Get daily forecast
            // -----------------------------------------

            if (isset($forecastData['daily'])) {

                $daily = $forecastData['daily'];

                // Today's sunrise / sunset
                $sunrise =
                    $daily['sunrise'][0] ?? null;

                $sunset =
                    $daily['sunset'][0] ?? null;

                $days =
                    count($daily['time'] ?? []);

                for (
                    $i = 0;
                    $i < min(5, $days);
                    $i++
                ) {

                    $weatherCode =
                        $daily['weather_code'][$i] ?? 0;

                    // -----------------------------------------
                    // Add forecast day
                    // -----------------------------------------

                    $forecast[] = [

                        'date' =>
                            $daily['time'][$i] ?? '',

                        'max' =>
                            $daily['temperature_2m_max'][$i]
                            ?? 0,

                        'min' =>
                            $daily['temperature_2m_min'][$i]
                            ?? 0,

                        'rain' =>
                            $daily['precipitation_probability_max'][$i]
                            ?? 0,

                        'sunrise' =>
                            $daily['sunrise'][$i] ?? '',

                        'sunset' =>
                            $daily['sunset'][$i] ?? '',

                        'icon' => match ($weatherCode) {

                            0
                                => '☀️',

                            1, 2, 3
                                => '🌤️',

                            45, 48
                                => '🌫️',

                            51, 53, 55, 56, 57
                                => '🌦️',

                            61, 63, 65,
                            66, 67,
                            80, 81, 82
                                => '🌧️',

                            71, 73, 75, 77,
                            85, 86
                                => '❄️',

                            95, 96, 99
                                => '⛈️',

                            default
                                => '🌤️',
                        },
                    ];
                }
            }
        }

        // -----------------------------------------
        // STEP 6: Hourly Weather Forecast
        // -----------------------------------------

        $hourlyResponse = Http::get(
            'https://api.open-meteo.com/v1/forecast',
            [
                'latitude' => $latitude,
                'longitude' => $longitude,

                'hourly' =>
                    'temperature_2m,weather_code',

                'timezone' => 'auto',

                'forecast_days' => 2,
            ]
        );

        $hourlyForecast = [];

        if ($hourlyResponse->successful()) {

            $hourlyData = $hourlyResponse->json();

            // -----------------------------------------
            // Update timezone from hourly API
            // -----------------------------------------

            $timezone =
                $hourlyData['timezone'] ?? $timezone;

            if (isset($hourlyData['hourly'])) {

                $hourly = $hourlyData['hourly'];

                // -----------------------------------------
                // Current hour
                // Based on LOCATION timezone
                // -----------------------------------------

                $currentHour =
                    now($timezone)->format('Y-m-d\TH:00');

                $totalHours =
                    count($hourly['time'] ?? []);

                for (
                    $i = 0;
                    $i < $totalHours;
                    $i++
                ) {

                    if (
                        $hourly['time'][$i]
                        >= $currentHour
                    ) {

                        $weatherCode =
                            $hourly['weather_code'][$i]
                            ?? 0;

                        // -----------------------------------------
                        // Add hourly forecast
                        // -----------------------------------------

                        $hourlyForecast[] = [

                            'time' =>
                                $hourly['time'][$i] ?? '',

                            'temperature' =>
                                $hourly['temperature_2m'][$i]
                                ?? 0,

                            'icon' => match ($weatherCode) {

                                0
                                    => '☀️',

                                1, 2, 3
                                    => '🌤️',

                                45, 48
                                    => '🌫️',

                                51, 53, 55,
                                56, 57
                                    => '🌦️',

                                61, 63, 65,
                                66, 67,
                                80, 81, 82
                                    => '🌧️',

                                71, 73, 75, 77,
                                85, 86
                                    => '❄️',

                                95, 96, 99
                                    => '⛈️',

                                default
                                    => '🌤️',
                            },
                        ];

                        // Show only next 12 hours
                        if (
                            count($hourlyForecast) >= 12
                        ) {
                            break;
                        }
                    }
                }
            }
        }

        // -----------------------------------------
        // STEP 7: Send data to Weather UI
        // -----------------------------------------

        return view('weather', [

            'city' =>
                $city,

            'country' =>
                $country,

            // Location timezone
            'timezone' =>
                $timezone,

            'temperature' =>
                $temperature,

            'condition' =>
                $condition,

            'weatherIcon' =>
                $weatherIcon,

            'humidity' =>
                $humidity,

            'wind' =>
                $wind,

            'feelsLike' =>
                $feelsLike,

            'uvIndex' =>
                $uvIndex,

            // Sunrise / Sunset
            'sunrise' =>
                $sunrise,

            'sunset' =>
                $sunset,

            'forecast' =>
                $forecast,

            'hourlyForecast' =>
                $hourlyForecast,
        ]);
    }
}