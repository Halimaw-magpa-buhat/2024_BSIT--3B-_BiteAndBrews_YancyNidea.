<?php
include('layouts/header.php');
include_once("server/connection.php");

$stmt = $conn->prepare("SELECT * FROM products WHERE is_deleted = FALSE");

$stmt->execute();

$products = $stmt->get_result();

?>





      <!---Must Try--->
      <section id="must try" class="my-5 py-5">
        <div class="container mt-5 py-5">
          <h3>MUST TRY</h3>
          <hr>
        </div>
        <div class="row mx-auto container">
          
        <?php while($row= $products->fetch_assoc()){ ?>

          <div onclick="window.location.href='single_menu.php';" class="best text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $row['product_img'];?>"/>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h5 class="b-name"><?php echo $row['product_name']; ?></h5>
            <h4 class="b-price">Php<?php echo $row['product_price']; ?></h4>
            <a class="btn order-btn" href="<?php echo "single_menu.php?product_id=".$row['product_id'];?>">Add To Basket</a>
          </div>
          <?php }?>

          

          <nav aria-label="Page navigation example">
            <ul class="pagination" mt-5>
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>




        </div>
      </section>
    

<?php include('layouts/footer.php');?>