<?php
  $autoloader = join(DIRECTORY_SEPARATOR,[__DIR__,'vendor','autoload.php']);
  require $autoloader;

  //We import the classes that we need
  use PHPOnCouch\CouchClient;
  use PHPOnCouch\Exceptions;

  //We create a client to access the database
  $client = new CouchClient('http://localhost:5984','products');

  //We get the database info just for the demo
  //var_dump($client->getDatabaseInfos());

?>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bare - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styling.css">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
      <div class="container">
        <a class="navbar-brand" href="#">Product Database</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="AddProduct.php">Add Product</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Support</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1>Welcome to your products database!</h1>
          <?php
          $all_docs = $client->getAllDocs();
          echo "Database got ".$all_docs->total_rows." documents.<BR>\n";
          foreach ( $all_docs->rows as $row ) { 
                $doc = $client->getDoc($row->id);
          ?>
            <div class="lineUp">  

              <?php echo "Document: ".$doc->_id.' Revision: '.$doc->_rev.' Name: '.$doc->name ?>

              <form action="update.php" method="post">
                <input type="hidden" name="productID" value="<?php echo $doc->_id ?>">
                <button type="submit" name = "submit">Update</button>
              </form>

              <form action="index.php" method="post">
                <input type="hidden" name="productNametoDelete" value="<?php echo $doc->_id ?>">
                <button type="submit" name = "submitDelete">Remove</button>
              </form>

              <?php
            }

            if(isset($_POST["updateButton"])){
               try {
                    $doc = $client->getDoc($_POST["productID"]);
                } catch (Exception $e) {
                    echo "ERROR: " . $e->getMessage() . " (" . $e->getCode() . ")<br>\n";
                }

                $doc->name = $_POST["name"];
                $doc->price = $_POST["price"];
                $doc->description = $_POST["desc"];

                try {
                  $response = $client->storeDoc($doc);
                } catch (Exception $e) {
                    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                }
            }

            if(isset($_POST["submitDelete"])){
              try {
                    $doc = $client->getDoc($_POST["productNametoDelete"]);
                } catch (Exception $e) {
                    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                }
              // permanently remove the document
              try {
                    $client->deleteDoc($doc);
                } catch (Exception $e) {
                    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                }
            }

            $view_fn="function(doc) { emit(doc.name,doc.description); }";
            $design_doc = new stdClass();
            $design_doc->_id = '_design/all';
            $design_doc->language = 'javascript';
            $design_doc->views = array ( 'by_date'=> array ('map' => $view_fn ) );
            $client->storeDoc($design_doc);
                     
          ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>