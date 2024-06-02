<?php include('layouts/header.php'); ?>

<!--Home-->
      <section id="Home">
        <div class="container">
        <h1>Good food, Good coffee, Good times.</h1>
        <p>Start your day right with savory bites and perfect brews.</p>
        <button>Treat Yourself Now</button>
      </div>
      </section>

<!--Brand-->
          <section id="brand" class="container">
            <div class="row">
                <img src="assets/imgs/brand.png">
            </div>
          </section>

<!----- featured products ----->
      
      <section id="featured" class="w-100">
        <div class="row p-0 m-0">
          <!--One-->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
              <a href="milktea.php">
              <img class="img-fluid" src="assets/imgs/cover1.jpg" alt="Milk Tea">
                      
              <div class="details">
              <h2>Milktea</h2>
              </div>
              </a>
        </div>
          <!--Two-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
          <a href="pastries.php">
            <img class="img-fluid" src="assets/imgs/cover2.jpg">
              <div class="details">
               <h2>Pastries</h2>
          </div>
          </a>
        </div>
          <!--Three-->
          <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
          <a href="coffee.php">
            <img class="img-fluid" src="assets/imgs/drink.jpg">
              <div class="details">
                 <h2>Brewed Coffee</h2>
          </div>
          </a>
        </div>
      </section>

      <!---Must Try--->
      <section id="must try" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
          <h3>ALL TIME FAVORITE</h3>
          <hr>
        </div>
        <div class="row mx-auto container-fluid">

        <?php include('server/get_musttry_products.php'); ?>
      
        <?php while( $row= $musttry_products->fetch_assoc()){ ?>
          
          <div class="best text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_img'];?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="b-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="b-price">Php <?php echo $row['product_price']; ?></h4>
            <a href=<?php echo "single_menu.php?product_id=". $row['product_id'] ?>><button class="order-btn">Add To Basket</button></a>
          </div>
         
        <?php }?>

        </div>
      </section>
    
      <!---Banner-->
      <section id="banner" class="my-5 pb-5">
        <div class="container">
          <h4>ESCAPE THE ORDINARY. SAVOR THE EXTRAORDINARY</h4>
          <button class="text-uppercase">treat yourself now</button>
        </div>
      </section>

       <!---Banner 2-->
       <section id="banner-two" class="my-5 pb-5">
        <div class="container">
        </div>
      </section>
    

  <?php include('layouts/footer.php');?>