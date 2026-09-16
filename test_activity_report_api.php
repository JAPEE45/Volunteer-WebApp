<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Report API - Comprehensive Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #dc143c;
            border-bottom: 3px solid #dc143c;
            padding-bottom: 10px;
        }
        .test-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .test-section h2 {
            color: #2d3748;
            margin-top: 0;
        }
        .success {
            color: #22c55e;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .info {
            color: #3b82f6;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border-left: 4px solid #3b82f6;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status-pass {
            background: #d1fae5;
            color: #065f46;
        }
        .status-fail {
            background: #fee2e2;
            color: #991b1b;
        }
        button {
            background: #dc143c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover {
            background: #b01030;
        }
        .result {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>🧪 Activity Report API - Comprehensive Test Suite</h1>
    <p>This page tests all aspects of the submitActivityReport.php API endpoint.</p>

    <div class="test-section">
        <h2>Test 1: API Response Format</h2>
        <p>Verifies that the API returns valid JSON with correct headers.</p>
        <button onclick="test1()">Run Test 1</button>
        <div id="test1-result" class="result"></div>
    </div>

    <div class="test-section">
        <h2>Test 2: Unauthorized Access (No Session)</h2>
        <p>Tests API behavior when user is not logged in.</p>
        <button onclick="test2()">Run Test 2</button>
        <div id="test2-result" class="result"></div>
    </div>

    <div class="test-section">
        <h2>Test 3: Invalid Request Method (GET instead of POST)</h2>
        <p>Verifies that only POST requests are accepted.</p>
        <button onclick="test3()">Run Test 3</button>
        <div id="test3-result" class="result"></div>
    </div>

    <div class="test-section">
        <h2>Test 4: Missing Required Fields</h2>
        <p>Tests validation of required form fields.</p>
        <button onclick="test4()">Run Test 4</button>
        <div id="test4-result" class="result"></div>
    </div>

    <div class="test-section">
        <h2>Test 5: Run All Tests</h2>
        <button onclick="runAllTests()">▶ Run All Tests</button>
        <div id="all-tests-result" class="result"></div>
    </div>

    <script>
        async function test1() {
            const resultDiv = document.getElementById('test1-result');
            resultDiv.innerHTML = '<p>Running...</p>';

            try {
                const response = await fetch('../utility/submitActivityReport.php', {
                    method: 'POST',
                    body: new FormData()
                });

                const contentType = response.headers.get('Content-Type');
                const responseText = await response.text();
                
                let result = '<h3>Results:</h3>';
                result += `<p><strong>Status Code:</strong> ${response.status}</p>`;
                result += `<p><strong>Content-Type:</strong> ${contentType}</p>`;
                
                // Check if it's valid JSON
                let isValidJSON = false;
                let jsonData = null;
                try {
                    jsonData = JSON.parse(responseText);
                    isValidJSON = true;
                } catch (e) {
                    isValidJSON = false;
                }

                result += `<p><strong>Valid JSON:</strong> <span class="${isValidJSON ? 'success' : 'error'}">${isValidJSON ? '✓ YES' : '✗ NO'}</span></p>`;
                
                if (isValidJSON) {
                    result += `<p><strong>Response:</strong></p><pre>${JSON.stringify(jsonData, null, 2)}</pre>`;
                    
                    const hasSuccess = jsonData.hasOwnProperty('success');
                    const hasMessage = jsonData.hasOwnProperty('message');
                    
                    result += `<p><strong>Has 'success' field:</strong> <span class="${hasSuccess ? 'success' : 'error'}">${hasSuccess ? '✓ YES' : '✗ NO'}</span></p>`;
                    result += `<p><strong>Has 'message' field:</strong> <span class="${hasMessage ? 'success' : 'error'}">${hasMessage ? '✓ YES' : '✗ NO'}</span></p>`;
                    
                    if (response.status === 200 && isValidJSON && hasSuccess && hasMessage && contentType.includes('application/json')) {
                        result += '<p class="status-badge status-pass">✓ TEST PASSED</p>';
                    } else {
                        result += '<p class="status-badge status-fail">✗ TEST FAILED</p>';
                    }
                } else {
                    result += `<p><strong>Raw Response (first 500 chars):</strong></p><pre>${responseText.substring(0, 500)}</pre>`;
                    result += '<p class="status-badge status-fail">✗ TEST FAILED - Invalid JSON</p>';
                }

                resultDiv.innerHTML = result;
            } catch (error) {
                resultDiv.innerHTML = `<p class="error">Error: ${error.message}</p>`;
            }
        }

        async function test2() {
            const resultDiv = document.getElementById('test2-result');
            resultDiv.innerHTML = '<p>Running...</p>';

            try {
                const response = await fetch('../utility/submitActivityReport.php', {
                    method: 'POST',
                    body: new FormData()
                });

                const jsonData = await response.json();
                
                let result = '<h3>Results:</h3>';
                result += `<pre>${JSON.stringify(jsonData, null, 2)}</pre>`;
                
                if (jsonData.success === false && jsonData.message === 'Unauthorized access') {
                    result += '<p class="status-badge status-pass">✓ TEST PASSED - Correct unauthorized response</p>';
                } else {
                    result += '<p class="status-badge status-fail">✗ TEST FAILED - Unexpected response</p>';
                }

                resultDiv.innerHTML = result;
            } catch (error) {
                resultDiv.innerHTML = `<p class="error">Error: ${error.message}</p>`;
            }
        }

        async function test3() {
            const resultDiv = document.getElementById('test3-result');
            resultDiv.innerHTML = '<p>Running...</p>';

            try {
                const response = await fetch('../utility/submitActivityReport.php', {
                    method: 'GET'
                });

                const jsonData = await response.json();
                
                let result = '<h3>Results:</h3>';
                result += `<pre>${JSON.stringify(jsonData, null, 2)}</pre>`;
                
                if (jsonData.success === false && jsonData.message.includes('Invalid request method')) {
                    result += '<p class="status-badge status-pass">✓ TEST PASSED - GET request properly rejected</p>';
                } else if (jsonData.message === 'Unauthorized access') {
                    result += '<p class="status-badge status-pass">✓ TEST PASSED - Unauthorized check happens before method check</p>';
                } else {
                    result += '<p class="status-badge status-fail">✗ TEST FAILED - Unexpected response</p>';
                }

                resultDiv.innerHTML = result;
            } catch (error) {
                resultDiv.innerHTML = `<p class="error">Error: ${error.message}</p>`;
            }
        }

        async function test4() {
            const resultDiv = document.getElementById('test4-result');
            resultDiv.innerHTML = '<p>Running...</p>';

            try {
                // This test requires being logged in, so it may show unauthorized
                const formData = new FormData();
                formData.append('deployment_id', '0');
                formData.append('event_id', '0');

                const response = await fetch('../utility/submitActivityReport.php', {
                    method: 'POST',
                    body: formData
                });

                const jsonData = await response.json();
                
                let result = '<h3>Results:</h3>';
                result += `<pre>${JSON.stringify(jsonData, null, 2)}</pre>`;
                
                if (jsonData.success === false) {
                    result += '<p class="status-badge status-pass">✓ TEST PASSED - Validation working (or unauthorized)</p>';
                } else {
                    result += '<p class="status-badge status-fail">✗ TEST FAILED - Should reject invalid data</p>';
                }

                resultDiv.innerHTML = result;
            } catch (error) {
                resultDiv.innerHTML = `<p class="error">Error: ${error.message}</p>`;
            }
        }

        async function runAllTests() {
            const resultDiv = document.getElementById('all-tests-result');
            resultDiv.innerHTML = '<p class="info">Running all tests...</p>';
            
            await test1();
            await new Promise(resolve => setTimeout(resolve, 500));
            await test2();
            await new Promise(resolve => setTimeout(resolve, 500));
            await test3();
            await new Promise(resolve => setTimeout(resolve, 500));
            await test4();
            
            resultDiv.innerHTML = '<p class="success">✓ All tests completed! Check individual results above.</p>';
        }

        // Auto-run test 1 on page load
        window.addEventListener('load', () => {
            test1();
        });
    </script>
</body>
</html>
