<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
            text-align: center;
        }
        .detail {
            margin-bottom: 20px;
        }
        .detail label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .detail p {
            display: inline-block;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- <h2>A form has been submitted on {{ \Carbon\Carbon::today()->isoFormat('Do MMMM YYYY') }} from {{ $ip }} - {{ $contact['country'] }}</h2> -->
        <div class="detail">
            <label>Name:</label>
            <!-- <p>{{ $contact['name'] }}</p> -->
        </div>
        <div class="detail">
            <label>Email:</label>
            <!-- <p>{{ $contact['email'] }}</p> -->
        </div>
        <div class="detail">
            <label>Phone:</label>
            <!-- <p>{{ $contact['phone'] }}</p> -->
        </div>
        <div class="detail">
            <label>Company:</label>
            <!-- <p>{{ $contact['company'] }}</p> -->
        </div>
        <div class="detail">
            <label>Subject:</label>
            <!-- <p>{{ $contact['subject'] }}</p> -->
        </div>
        <div class="detail">
            <label>Message:</label>
            <!-- <p>{{ $contact['message'] }}</p> -->
        </div>
    </div>

</body>
</html>
