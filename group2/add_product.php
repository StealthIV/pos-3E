<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');

if(isset($_POST['add_product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','low-stock-quantity', 'buying-price', 'saleing-price');
    validate_fields($req_fields);
    
    if(empty($errors)){
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_ls   = remove_junk($db->escape($_POST['low-stock-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        
        if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
            $media_id = '0';
        } else {
            $media_id = remove_junk($db->escape($_POST['product-photo']));
        }
        
        $date    = make_date();
        
        $barcode_query = "SELECT barcode FROM warehouse WHERE name = '{$p_name}' AND categorie_id = '{$p_cat}'";
        $barcode_result = $db->query($barcode_query);

        if($db->num_rows($barcode_result) > 0){
            $barcode_data = $db->fetch_assoc($barcode_result);
            $barcode = $barcode_data['barcode'];

            $insert_product_query = "INSERT INTO products (name, quantity, low_stock_quantity, buy_price, sale_price, categorie_id, media_id, barcode, date) VALUES ('{$p_name}', '{$p_qty}', '{$p_ls}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$barcode}', '{$date}')";

            if($db->query($insert_product_query)){
                $warehouse_query = "SELECT quantity FROM warehouse WHERE name = '{$p_name}' AND categorie_id = '{$p_cat}'";
                $warehouse_qresult = $db->query($warehouse_query);

                if($db->num_rows($warehouse_qresult) > 0){
                    $warehouse_data = $db->fetch_assoc($warehouse_qresult);
                    $warehouse_qty = $warehouse_data['quantity'];

                    if($warehouse_qty >= $p_qty){
                        $new_warehouse_qty = $warehouse_qty - $p_qty;
                        $update_warehouse_query = "UPDATE warehouse SET quantity = '{$new_warehouse_qty}' WHERE name = '{$p_name}' AND categorie_id = '{$p_cat}'";

                        if($db->query($update_warehouse_query)){
                            $session->msg('s', "Product added and warehouse quantity updated");
                            redirect('add_product.php', false);
                        } else {
                            $session->msg('d', 'Failed to update warehouse quantity.');
                            redirect('add_product.php', false);
                        }
                    } else {
                        $session->msg('d', 'Insufficient quantity in warehouse.');
                        redirect('add_product.php', false);
                    }
                } else {
                    $session->msg('d', 'Product not found in warehouse.');
                    redirect('add_product.php', false);
                }
            } else {
                $session->msg('d', 'Failed to add product to products table.');
                redirect('add_product.php', false);
            }
        } else {
            $session->msg('d', 'Barcode not found in warehouse.');
            redirect('add_product.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Product Title">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Select Product Photo</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-5">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-5">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="low-stock-quantity" placeholder="Low Stock Quantity">
                  </div>
                 </div>
                </div>
              </div>
              <div class="form-group">
               <div class="row">
                 <div class="col-md-5">
                   <div class="input-group">
                     <span class="input-group-addon">
                     <span>&#8369;</span>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <span class="input-group-addon">
                      <span>&#8369;</span>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
