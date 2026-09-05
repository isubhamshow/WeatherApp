<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $city ?? 'Weather' }} Weather
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }


        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #111827;
        }


        /* =========================================
           PAGE BACKGROUND
        ========================================= */

        .weather-page {

            min-height: 100vh;

            padding: 35px 15px;

            transition:
                background 0.5s ease;
        }


        .weather-page.sunny {

            background:
                linear-gradient(
                    135deg,
                    #56ccf2,
                    #f2c94c
                );
        }


        .weather-page.cloud {

            background:
                linear-gradient(
                    135deg,
                    #8e9eab,
                    #eef2f3
                );
        }


        .weather-page.rain {

            background:
                linear-gradient(
                    135deg,
                    #314755,
                    #26a0da
                );
        }


        .weather-page.storm {

            background:
                linear-gradient(
                    135deg,
                    #232526,
                    #414345
                );
        }


        .weather-page.snow {

            background:
                linear-gradient(
                    135deg,
                    #83a4d4,
                    #b6fbff
                );
        }


        .weather-page.fog {

            background:
                linear-gradient(
                    135deg,
                    #757f9a,
                    #d7dde8
                );
        }


        .weather-page.default {

            background:
                linear-gradient(
                    135deg,
                    #74ebd5,
                    #9face6
                );
        }


        /* =========================================
           MAIN CONTAINER
        ========================================= */

        .container {

            width: 100%;

            max-width: 650px;

            margin: 0 auto;
        }


        /* =========================================
           WEATHER CARD
        ========================================= */

        .weather-card {

            background:
                rgba(255, 255, 255, 0.94);

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            border-radius: 28px;

            padding: 28px;

            box-shadow:
                0 25px 70px
                rgba(0, 0, 0, 0.18);
        }


        /* =========================================
           SEARCH
        ========================================= */

        .search-form {

            display: flex;

            gap: 10px;

            margin-bottom: 12px;
        }


        .search-input {

            flex: 1;

            min-width: 0;

            height: 52px;

            padding:
                0 17px;

            border:
                1px solid #d1d5db;

            border-radius: 14px;

            font-size: 16px;

            outline: none;

            background: white;
        }


        .search-input:focus {

            border-color:
                #6366f1;

            box-shadow:
                0 0 0 3px
                rgba(99, 102, 241, 0.12);
        }


        .search-button {

            height: 52px;

            padding:
                0 20px;

            border: none;

            border-radius: 14px;

            background:
                #111827;

            color: white;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition:
                0.2s ease;
        }


        .search-button:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 20px
                rgba(0, 0, 0, 0.18);
        }


        /* =========================================
           LOCATION BUTTON
        ========================================= */

        .location-button {

            width: 100%;

            height: 52px;

            border: none;

            border-radius: 14px;

            background:
                #eef2ff;

            color:
                #3730a3;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition:
                0.2s ease;

            margin-bottom: 25px;
        }


        .location-button:hover {

            background:
                #e0e7ff;

            transform:
                translateY(-2px);
        }


        .location-button:disabled {

            cursor: wait;

            opacity: 0.7;

            transform: none;
        }


        /* =========================================
           LOCATION STATUS
        ========================================= */

        .location-status {

            display: none;

            margin:
                -12px 0 20px;

            padding:
                10px 12px;

            border-radius: 10px;

            background:
                #f3f4f6;

            color:
                #4b5563;

            font-size: 13px;

            text-align: center;
        }


        /* =========================================
           CITY
        ========================================= */

        .city {

            text-align: center;

            font-size: 30px;

            font-weight: 800;

            color:
                #111827;

            margin-top: 5px;
        }


        .country {

            text-align: center;

            color:
                #6b7280;

            font-size: 14px;

            margin-top: 5px;
        }


        /* =========================================
           CURRENT WEATHER
        ========================================= */

        .weather-main {

            text-align: center;

            padding:
                25px 0 30px;
        }


        .weather-icon {

            font-size: 82px;

            line-height: 1;

            margin-bottom: 15px;
        }


        .temperature {

            font-size: 72px;

            line-height: 1;

            font-weight: 800;

            letter-spacing: -3px;

            color:
                #111827;
        }


        .condition {

            margin-top: 15px;

            font-size: 20px;

            font-weight: 600;

            color:
                #4b5563;
        }


        /* =========================================
           CURRENT WEATHER INFO
        ========================================= */

        .weather-info {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 12px;
        }


        .info-box {

            background:
                #f8fafc;

            border:
                1px solid #e5e7eb;

            border-radius: 17px;

            padding: 18px;

            text-align: center;

            transition:
                0.2s ease;
        }


        .info-box:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 8px 20px
                rgba(0, 0, 0, 0.07);
        }


        .info-title {

            font-size: 13px;

            color:
                #6b7280;

            margin-bottom: 7px;
        }


        .info-value {

            font-size: 20px;

            font-weight: 800;

            color:
                #111827;
        }


        /* =========================================
           SECTION
        ========================================= */

        .forecast-section {

            margin-top: 30px;

            padding-top: 25px;

            border-top:
                1px solid #e5e7eb;
        }


        .section-title {

            margin: 0 0 18px;

            font-size: 20px;

            font-weight: 800;

            color:
                #111827;
        }


        /* =========================================
           5 DAY FORECAST
        ========================================= */

        .forecast-list {

            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 8px;
        }


        .forecast-day {

            background:
                #f8fafc;

            border:
                1px solid #e5e7eb;

            border-radius: 15px;

            padding:
                12px 5px;

            text-align: center;
        }


        .forecast-date {

            font-size: 12px;

            font-weight: 700;

            color:
                #6b7280;
        }


        .forecast-icon {

            font-size: 27px;

            margin:
                9px 0;
        }


        .forecast-temp {

            font-size: 16px;

            font-weight: 800;

            color:
                #111827;
        }


        .forecast-min {

            margin-top: 3px;

            font-size: 13px;

            color:
                #9ca3af;
        }


        .forecast-rain {

            margin-top: 6px;

            font-size: 11px;

            color:
                #2563eb;
        }


        /* =========================================
           HOURLY FORECAST
        ========================================= */

        .hourly-list {

            display: flex;

            gap: 10px;

            overflow-x: auto;

            padding:
                3px 2px 12px;

            scrollbar-width: thin;
        }


        .hourly-item {

            min-width: 78px;

            flex-shrink: 0;

            background:
                #f8fafc;

            border:
                1px solid #e5e7eb;

            border-radius: 15px;

            padding:
                12px 8px;

            text-align: center;
        }


        .hourly-time {

            font-size: 12px;

            font-weight: 700;

            color:
                #6b7280;
        }


        .hourly-icon {

            font-size: 27px;

            margin:
                9px 0;
        }


        .hourly-temp {

            font-size: 16px;

            font-weight: 800;

            color:
                #111827;
        }


        /* =========================================
           ERROR POPUP
        ========================================= */

        .error-popup {

            position: fixed;

            top: 20px;

            left: 50%;

            transform:
                translateX(-50%);

            width:
                min(92%, 500px);

            z-index: 9999;
        }


        .error-content {

            display: flex;

            align-items: center;

            gap: 12px;

            padding:
                15px 17px;

            background:
                white;

            border:
                1px solid #fecaca;

            border-radius: 15px;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.18);
        }


        .error-icon {

            font-size: 25px;
        }


        .error-text {

            flex: 1;
        }


        .error-title {

            font-size: 14px;

            font-weight: 800;

            color:
                #991b1b;
        }


        .error-message {

            margin-top: 3px;

            font-size: 13px;

            color:
                #6b7280;
        }


        .error-close {

            border: none;

            background: transparent;

            font-size: 24px;

            cursor: pointer;

            color:
                #6b7280;
        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 600px) {

            .weather-page {

                padding:
                    15px 10px;
            }


            .weather-card {

                padding:
                    20px 15px;

                border-radius: 22px;
            }


            .search-form {

                flex-direction: column;
            }


            .search-button {

                width: 100%;
            }


            .city {

                font-size: 27px;
            }


            .temperature {

                font-size: 60px;
            }


            .weather-icon {

                font-size: 70px;
            }


            .condition {

                font-size: 18px;
            }


            .forecast-list {

                gap: 5px;
            }


            .forecast-day {

                padding:
                    10px 3px;
            }


            .forecast-icon {

                font-size: 23px;
            }


            .forecast-temp {

                font-size: 14px;
            }


            .forecast-min {

                font-size: 12px;
            }

        }

            /* =========================================
            CURRENT DATE & TIME
            ========================================= */

            .current-datetime {
                text-align: center;
                margin-top: 12px;
                margin-bottom: 5px;
            }

            .current-date {
                font-size: 14px;
                font-weight: 600;
                color: #6b7280;
            }

            .current-time {
                margin-top: 4px;
                font-size: 25px;
                font-weight: 800;
                color: #111827;
                letter-spacing: 0.5px;
            }    
    </style>


    <!-- =========================================
         JAVASCRIPT
    ========================================== -->

    <script>

        /* =========================================
           ERROR POPUP
        ========================================= */

        function closeErrorPopup() {

            const popup =
                document.getElementById(
                    'errorPopup'
                );

            if (popup) {

                popup.remove();
            }
        }


        setTimeout(function () {

            closeErrorPopup();

        }, 5000);


        /* =========================================
           SHOW LOCATION STATUS
        ========================================= */

        function showLocationStatus(message) {

            const status =
                document.getElementById(
                    'locationStatus'
                );


            if (status) {

                status.style.display =
                    'block';

                status.innerHTML =
                    message;
            }
        }


        /* =========================================
           HIDE LOCATION STATUS
        ========================================= */

        function hideLocationStatus() {

            const status =
                document.getElementById(
                    'locationStatus'
                );


            if (status) {

                status.style.display =
                    'none';
            }
        }


        /* =========================================
           RESET LOCATION BUTTON
        ========================================= */

        function resetLocationButton() {

            const button =
                document.querySelector(
                    '.location-button'
                );


            if (button) {

                button.disabled = false;

                button.innerHTML =
                    '📍 Use My Location';
            }
        }


        /* =========================================
           OPEN WEATHER USING COORDINATES
        ========================================= */

        function openWeather(
            latitude,
            longitude
        ) {

            console.log(
                'Latitude:',
                latitude
            );

            console.log(
                'Longitude:',
                longitude
            );


            const url =
                new URL(
                    '/weather',
                    window.location.origin
                );


            url.searchParams.set(
                'latitude',
                latitude
            );


            url.searchParams.set(
                'longitude',
                longitude
            );


            console.log(
                'Opening:',
                url.toString()
            );


            window.location.href =
                url.toString();
        }


        /* =========================================
           IP LOCATION FALLBACK
        ========================================== */

        function getIPLocation() {

            console.log(
                'Trying IP location fallback...'
            );


            showLocationStatus(
                '🌐 Finding your approximate location...'
            );


            const button =
                document.querySelector(
                    '.location-button'
                );


            if (button) {

                button.disabled = true;

                button.innerHTML =
                    '🌐 Finding Location...';
            }


            fetch(
                'https://ipapi.co/json/',
                {
                    method: 'GET'
                }
            )

            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        'IP location request failed'
                    );
                }


                return response.json();

            })


            .then(function(data) {

                console.log(
                    'IP location response:',
                    data
                );


                const latitude =
                    parseFloat(
                        data.latitude
                    );


                const longitude =
                    parseFloat(
                        data.longitude
                    );


                if (
                    isNaN(latitude) ||
                    isNaN(longitude)
                ) {

                    throw new Error(
                        'Invalid coordinates'
                    );
                }


                showLocationStatus(
                    '✅ Location found. Loading weather...'
                );


                openWeather(
                    latitude,
                    longitude
                );

            })


            .catch(function(error) {

                console.error(
                    'IP location error:',
                    error
                );


                hideLocationStatus();

                resetLocationButton();


                alert(
                    'Unable to detect your location automatically. Please search your city manually.'
                );

            });

        }


        /* =========================================
           USE MY LOCATION
        ========================================== */

        function getMyLocation() {

            console.log(
                '📍 Use My Location clicked'
            );


            hideLocationStatus();


            const button =
                document.querySelector(
                    '.location-button'
                );


            if (button) {

                button.disabled = true;

                button.innerHTML =
                    '📍 Detecting Location...';
            }


            /* -------------------------------------
               Check browser geolocation
            ------------------------------------- */

            if (
                !navigator.geolocation
            ) {

                console.warn(
                    'Geolocation is not supported'
                );


                getIPLocation();

                return;
            }


            showLocationStatus(
                '📍 Detecting your current location...'
            );


            /* -------------------------------------
               Request browser location
            ------------------------------------- */

            navigator.geolocation.getCurrentPosition(

                /* =================================
                   SUCCESS
                ================================= */

                function(position) {

                    console.log(
                        '✅ GPS location detected'
                    );


                    const latitude =
                        position.coords.latitude;


                    const longitude =
                        position.coords.longitude;


                    showLocationStatus(
                        '✅ Location detected. Loading weather...'
                    );


                    openWeather(
                        latitude,
                        longitude
                    );

                },


                /* =================================
                   ERROR

                   Instead of showing timeout,
                   automatically use IP location.
                ================================= */

                function(error) {

                    console.warn(
                        'GPS failed:',
                        error
                    );


                    console.log(
                        'Switching to IP location...'
                    );


                    getIPLocation();

                },


                /* =================================
                   OPTIONS
                ================================= */

                {

                    /*
                     * Do NOT force GPS.
                     * Browser can use Wi-Fi/network
                     * location which is faster on PC.
                     */

                    enableHighAccuracy: false,


                    /*
                     * Wait maximum 10 seconds.
                     * If it fails, IP fallback starts.
                     */

                    timeout: 10000,


                    /*
                     * Accept a location cached
                     * within the last 10 minutes.
                     */

                    maximumAge: 600000

                }

            );

        }
        
        /* =========================================
        LIVE DATE & TIME
        ========================================= */

        function updateDateTime() {

            const dateElement =
                document.getElementById('currentDate');

            const timeElement =
                document.getElementById('currentTime');

            const datetimeContainer =
                document.querySelector('.current-datetime');

            if (!dateElement || !timeElement || !datetimeContainer) {
                return;
            }

            const timezone =
                datetimeContainer.dataset.timezone ||
                'Asia/Kolkata';

            const now = new Date();

            try {

                const dateFormatter =
                    new Intl.DateTimeFormat('en-IN', {
                        timeZone: timezone,
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });

                const timeFormatter =
                    new Intl.DateTimeFormat('en-IN', {
                        timeZone: timezone,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });

                dateElement.textContent =
                    dateFormatter.format(now);

                timeElement.textContent =
                    timeFormatter.format(now);

            } catch (error) {

                console.error(
                    'Timezone error:',
                    error
                );

            }
        }


        /* =========================================
        START LIVE CLOCK
        ========================================= */

        updateDateTime();

        setInterval(
            updateDateTime,
            1000
        );

        /* =========================================
           PAGE LOAD
        ========================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                console.log(
                    'Weather application loaded'
                );

            }
        );

    </script>

</head>


@php

    /* =========================================
       WEATHER BACKGROUND CLASS
    ========================================= */

    $weatherClass = 'default';


    $conditionText =
        strtolower(
            $condition ?? ''
        );


    if (
        str_contains(
            $conditionText,
            'thunder'
        )
    ) {

        $weatherClass = 'storm';

    }

    elseif (
        str_contains(
            $conditionText,
            'rain'
        )
        ||
        str_contains(
            $conditionText,
            'drizzle'
        )
    ) {

        $weatherClass = 'rain';

    }

    elseif (
        str_contains(
            $conditionText,
            'snow'
        )
    ) {

        $weatherClass = 'snow';

    }

    elseif (
        str_contains(
            $conditionText,
            'fog'
        )
    ) {

        $weatherClass = 'fog';

    }

    elseif (
        str_contains(
            $conditionText,
            'cloud'
        )
    ) {

        $weatherClass = 'cloud';

    }

    elseif (
        str_contains(
            $conditionText,
            'clear'
        )
    ) {

        $weatherClass = 'sunny';

    }

@endphp


<body
    class="weather-page {{ $weatherClass }}"
>


    <div class="container">


        <div class="weather-card">


            <!-- =====================================
                 SEARCH FORM
            ====================================== -->

            <form
                method="GET"
                action="{{ url('/weather') }}"
                class="search-form"
            >

                <input
                    type="text"
                    name="city"
                    class="search-input"
                    placeholder="Enter city name..."
                    value="{{ request('city', '') }}"
                    autocomplete="off"
                    required
                >


                <button
                    type="submit"
                    class="search-button"
                >

                    🔍 Search

                </button>

            </form>


            <!-- =====================================
                 USE MY LOCATION
            ====================================== -->

            <button
                type="button"
                class="location-button"
                onclick="getMyLocation()"
            >

                📍 Use My Location

            </button>


            <!-- =====================================
                 LOCATION STATUS
            ====================================== -->

            <div
                id="locationStatus"
                class="location-status"
            ></div>


            <!-- =====================================
                 ERROR MESSAGE
            ====================================== -->

            @if(session('error'))

                <div
                    id="errorPopup"
                    class="error-popup"
                >

                    <div
                        class="error-content"
                    >

                        <div
                            class="error-icon"
                        >
                            ⚠️
                        </div>


                        <div
                            class="error-text"
                        >

                            <div
                                class="error-title"
                            >
                                Weather Unavailable
                            </div>


                            <div
                                class="error-message"
                            >
                                {{ session('error') }}
                            </div>

                        </div>


                        <button
                            type="button"
                            class="error-close"
                            onclick="closeErrorPopup()"
                        >

                            ×

                        </button>

                    </div>

                </div>

            @endif


            <!-- =====================================
                 CITY
            ====================================== -->

            <div class="city">

                {{ $city ?? 'Unknown Location' }}

            </div>


            @if(!empty($country))

                <div class="country">

                    {{ $country }}

                </div>

            @endif

            <!-- =====================================
                CURRENT DATE & TIME
            ====================================== -->

            <div
                class="current-datetime"
                data-timezone="{{ $timezone ?? 'UTC' }}"
            >

                <div
                    id="currentDate"
                    class="current-date"
                >
                    Loading date...
                </div>

                <div
                    id="currentTime"
                    class="current-time"
                >
                    Loading time...
                </div>

            </div>


            <!-- =====================================
                 CURRENT WEATHER
            ====================================== -->

            <div class="weather-main">


                <div class="weather-icon">

                    {{ $weatherIcon ?? '🌤️' }}

                </div>


                <div class="temperature">

                    {{ round($temperature ?? 0) }}°C

                </div>


                <div class="condition">

                    {{ $condition ?? 'Unknown' }}

                </div>


            </div>


            <!-- =====================================
                 WEATHER INFORMATION
            ====================================== -->

            <div class="weather-info">


                <!-- FEELS LIKE -->

                <div class="info-box">

                    <div class="info-title">

                        Feels Like

                    </div>


                    <div class="info-value">

                        {{ round($feelsLike ?? 0) }}°C

                    </div>

                </div>


                <!-- HUMIDITY -->

                <div class="info-box">

                    <div class="info-title">

                        Humidity

                    </div>


                    <div class="info-value">

                        {{ $humidity ?? 0 }}%

                    </div>

                </div>


                <!-- WIND -->

                <div class="info-box">

                    <div class="info-title">

                        Wind

                    </div>


                    <div class="info-value">

                        {{ round($wind ?? 0) }} km/h

                    </div>

                </div>


                <!-- UV -->

                <div class="info-box">

                    <div class="info-title">

                        UV Index

                    </div>


                    <div class="info-value">

                        {{ $uvIndex ?? 0 }}

                    </div>

                </div>


            </div>


            <!-- =====================================
                 5 DAY FORECAST
            ====================================== -->

            @if(!empty($forecast))


                <div
                    class="forecast-section"
                >


                    <h2
                        class="section-title"
                    >

                        📅 5-Day Forecast

                    </h2>


                    <div
                        class="forecast-list"
                    >


                        @foreach(
                            $forecast
                            as $day
                        )


                            <div
                                class="forecast-day"
                            >


                                <div
                                    class="forecast-date"
                                >

                                    @if($loop->first)

                                        Today

                                    @else

                                        {{ \Carbon\Carbon::parse($day['date'])->format('D') }}

                                    @endif

                                </div>


                                <div
                                    class="forecast-icon"
                                >

                                    {{ $day['icon'] }}

                                </div>


                                <div
                                    class="forecast-temp"
                                >

                                    {{ round($day['max']) }}°

                                </div>


                                <div
                                    class="forecast-min"
                                >

                                    {{ round($day['min']) }}°

                                </div>


                                <div
                                    class="forecast-rain"
                                >

                                    💧
                                    {{ $day['rain'] }}%

                                </div>


                            </div>


                        @endforeach


                    </div>

                </div>


            @endif


            <!-- =====================================
                 HOURLY FORECAST
            ====================================== -->

            @if(!empty($hourlyForecast))


                <div
                    class="forecast-section"
                >


                    <h2
                        class="section-title"
                    >

                        🕐 Hourly Forecast

                    </h2>


                    <div
                        class="hourly-list"
                    >


                        @foreach(
                            $hourlyForecast
                            as $hour
                        )


                            <div
                                class="hourly-item"
                            >


                                <div
                                    class="hourly-time"
                                >

                                    {{
                                        \Carbon\Carbon::parse(
                                            $hour['time']
                                        )->format('g A')
                                    }}

                                </div>


                                <div
                                    class="hourly-icon"
                                >

                                    {{ $hour['icon'] }}

                                </div>


                                <div
                                    class="hourly-temp"
                                >

                                    {{ round($hour['temperature']) }}°

                                </div>


                            </div>


                        @endforeach


                    </div>

                </div>


            @endif


        </div>


    </div>


</body>

</html>