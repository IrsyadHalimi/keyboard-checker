<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .btn-submit {
                color: black;
                background-color: blue;
                height: 70px;
                width: 100px;
            }
            btn-submit::hover {
                height: 80px;
                width: 110px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Keyboard Health Check
                </div>
                <h2>Press key to test the keyboard. Results will be displayed below:</h2>
                <ul id="key-results"></ul>
                <button onclick="submitTestResult()" style="font-size: 20px; color: grey; height: 70px; width: 150px;">Submit Test Results</button>
            </div>
        </div>
        <script>
            const pressedKeys = {};
            const keyTestResult = {};

            // Detect key presses
            document.addEventListener('keydown', (event) => {
                const key = event.key;
                if (!pressedKeys[key]) {
                    pressedKeys[key] = Date.now();
                    console.log(`Key pressed: ${key}`);
                }
            });

            // Detect key releases
            document.addEventListener('keyup', (event) => {
                const key = event.key;
                if (pressedKeys[key]) {
                    const timeHeld = Date.now() - pressedKeys[key];
                    keyTestResult[key] = timeHeld;
                    console.log(`Key released: ${key}, held for ${timeHeld} ms`);
                    delete pressedKeys[key];
                }
            });

            // Submit test results to backend
            function submitTestResult() {
                fetch('/keyboard-test', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    body: JSON.stringify(keyTestResult)
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log('Server Response:', data);
                    displayResults(data); // Display backend response
                    clearResults(); // Clear the local results after submission
                })
                .catch(error => console.error('Error:', error));
            }

            // Display results in the list
            function displayResults(results) {
                const resultList = document.getElementById('key-results');
                resultList.innerHTML = ''; // Clear previous results
                const keyClick = document.createElement('h1');
                const statusResult = document.createElement('h1');
                keyClick.textContent = `Key: ${results.key}`;
                statusResult.textContent = `Status: ${results.status}`;
                resultList.appendChild(keyClick);
                resultList.appendChild(statusResult);
            
            }

            // Clear local test data
            function clearResults() {
                for (const key in keyTestResult) {
                    delete keyTestResult[key];
                }
            }
        </script>
    </body>
</html>
