

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDoor - Get it Deliver</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="css/home.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" rel="stylesheet">
   
    <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
    <?php
      include 'top_navbar.php';
     ?>
     <?php
      include 'bottom_navbar.php';
     ?>
    </header>
     <div class="container-sm">

        
    
            <div class="row oo">
                <div class="col">
                    <img src="images/banner.gif" class="img-fluid mx-auto d-block"  alt="...">
                  </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 col-sm-6  ok"><a href="book_activity.php"><img src="images/reserve.gif"class="img-fluid mx-auto shadow rounded-3" ></a></div>
                    <div class="col-6 col-sm-6 ok"><a href="hyperlocal.php"><img src="images/hyperlocal.gif"class="img-fluid mx-auto shadow rounded-3"></a></div>
                
                    <!-- Force next columns to break to new line -->
                    <div class="w-100"></div>
                
                    <div class="col-6 col-sm-6 ok"><a href="booking.php"><img src="images/yatra.gif"class="img-fluid mx-auto shadow rounded-3"></a></div>
                    <div class="col-6 col-sm-6 ok"><a href="maintenance.html"><img src="images/qr.gif"class="img-fluid mx-auto shadow rounded-3" ></a></div>
                  </div>
                  <br>
                  <div class="row text-center gap1">
                    <div class="col">
                      <h6 style="font-family: Josefin Sans, sans-serif;" class="sm-heading">
                        <i class="fa-solid fa-cart-shopping"></i> Some Featured Offers
                        <small class="text-body-secondary">Just For You</small>
                      </h6>
                      </div>
                      </div>
                      <div class="row gap1">
                        <div class="col">
                          <div id="carouselExampleControlsNoTouching" class="carousel carousel-dark slide" data-bs-touch="true" data-bs-interval="false">
                          
                            <div class="carousel-inner">
                              <div class="carousel-item active">
                                <img src="images/img1.jpg" class="d-block w-60 mx-auto d-block" alt="...">
                              </div>
                              <div class="carousel-item">
                                <img src="images/img1.jpg" class="d-block w-60 mx-auto d-block" alt="...">
                              </div>
                              <div class="carousel-item">
                                <img src="images/img1.jpg" class="d-block w-60 mx-auto d-block" alt="...">
                              </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" style="color: black;" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                          </div>                       
                           </div>
                    </div>
                    <div class="row gap">
                      <div class="col">
                        <p class="large" style="font-family: Josefin Sans, sans-serif; color: #313638;" class="sm-heading">
                         Get<br> it Deliver!
                        </p> 
                        <p>
                          <small class="text-body-sendary">Crafted With <i class="fa-solid fa-heart" style="color:#DE3163;"></i> in Phalodi, India</small>

                        </p>
                      </div>
                    </div>
                    
                          
      </div>
   
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      
    </body>

</html>