<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDoor - Select Rickshaw</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }
        .header {
            background-color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .header .back-arrow {
            margin-right: 15px;
            cursor: pointer;
            font-size: 24px;
            color: #f09e05;
        }
        .container {
            padding: 20px;
        }
        .card {
          
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card img {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            width: 100%;
            height: auto;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-family: 'Josefin Sans', sans-serif;
            color: #333;
            font-size: 18px;
            margin: 0;
        }
        .card-text {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .rating {
            color: #f09e05;
            margin-top: 5px;
        }
        .btn-select {
            background-color: #f09e05;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        .footer {
            background-color: white;
            padding: 15px;
            text-align: center;
            color: #999;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        .footer strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="home.html">
            <i class="fas fa-arrow-left back-arrow"></i>
        </a>
        <h1>DDoor - Yatra</h1>
    </div>

    <div class="container justify-content-center">
    <div class="row">
     <div class="col-sm">
        <div class="card">
        
            <img src="images/y1.webp" style="width:300px;" alt="Rickshaw Image">
            <div class="card-body">
                <h5 class="card-title">Driver: Bhau</h5>
                <p class="card-text">Rating: <span class="rating">★★★★★</span></p>
                <p class="card-text">Stand Address: Laxmipura</p>
                <button class="btn-select">Select</button>
            </div>
        </div>
     </div>
     <div class="col-sm">
        <div class="card">
            <img src="images/y2.webp" style="width:300px;"alt="Rickshaw Image">
            <div class="card-body">
                <h5 class="card-title">Driver: Unknown</h5>
                <p class="card-text">Rating: <span class="rating">★★★★★</span></p>
                <p class="card-text">Stand Address: Bhaiya Nadi</p>
                <button class="btn-select">Select</button>
            </div>
        </div>
    </div>
    <div class="col-sm">
        <div class="card">
            <img src="images/y3.webp" style="width:300px;"   alt="Rickshaw Image">
            <div class="card-body">
                <h5 class="card-title">Driver: Unknown</h5>
                <p class="card-text">Rating: <span class="rating">★★★★★</span></p>
                <p class="card-text">Stand Address: Adarsh Nagar</p>
                <button class="btn-select">Select</button>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="footer">
        <strong>&copy; 2024 DDoor. All rights reserved.</strong>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
