<!DOCTYPE html>
<html>
<head>
<head>
    <title>QR Code Generator</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.0/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Other styles and scripts -->
</head>
    <title>QR Code Generator</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.0/html5-qrcode.min.js"></script>
    <style>
        body {
            background-color: #f5f5f5; /* Light gray background */
            font-family: Arial, sans-serif;
            margin: 10;
            padding: 10;
            background-image: url('bg-qr.jpg');
            background-size: cover;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff; /* White container background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        h1 {
            color: #00008B; /* Dark blue text color */
            text-align: center;
            margin-top: 20px;
        }

        #qr-reader {
            margin: 0 auto;
            text-align: center;
            margin-bottom: 20px;
        }

        #message-id {
            color: #8B4513; /* Brown text color */
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }

        .copy-button {
            background-color: #4CAF50; /* Green button background color */
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease; /* Smooth hover transition */
        }

        .copy-button:hover {
            background-color: #45a049; /* Darker green button background color on hover */
        }

        .copy-button:disabled {
            background-color: #A9A9A9; /* Grayed out button background color */
            cursor: not-allowed;
        }

        .back-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<body>
    <a href="#" class="back-icon" onclick="goBack()"><i class="fas fa-chevron-left"></i></a> <!-- Back icon -->
    <!-- Rest of your HTML content -->
</body>
    <div class="container">
        <h1>QR Code Generator</h1>
        <div id="qr-reader"></div>
        <div id="message-id"></div> <!-- Container for displaying message or ID -->
        <button class="copy-button" disabled>Copy ID</button> <!-- Copy button -->
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function onScanSuccess(qrCodeMessage) {
            // Display the scanned QR code message or ID on the web page
            var messageIdElement = document.getElementById("message-id");
            messageIdElement.innerText = `Scanned message or ID: ${qrCodeMessage}`;

            // Enable copy button and store the ID in a data attribute
            var copyButton = document.querySelector(".copy-button");
            copyButton.dataset.idToCopy = qrCodeMessage;
            copyButton.disabled = false;
        }

        // Function to copy the ID when the copy button is clicked
        document.querySelector(".copy-button").addEventListener("click", function() {
            var idToCopy = this.dataset.idToCopy;
            navigator.clipboard.writeText(idToCopy).then(function() {
                alert("ID copied to clipboard: " + idToCopy);
            }, function() {
                alert("Copying failed. Please try again.");
            });
        });

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>
