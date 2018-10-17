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

<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  


<?php
// define variables and set to empty values
$nameErr = $pricesErr = $descErr = "";
$name = $price = $desc = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST["name"]);
    $price = test_input($_POST["price"]);
    $desc = test_input($_POST["desc"]); 
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Please enter your new product here!</h2>

<?php
	if(isset($_POST["submitAdd"])){
		$new_doc = new stdClass();
		$new_doc->name = $name;
		$new_doc->price = $price;
		$new_doc->description = $desc;
		try {
		    $response = $client->storeDoc($new_doc);
		} catch (Exception $e) {
		    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
		}
	}
?>

<form method="post" action="AddProduct.php">  
  Product Name: <input type="text" name="name" value="<?php echo $name;?>">
  <br><br>
  Price: <input type="text" name="price" value="<?php echo $price;?>">
  <br><br>
  Description: <input type="" name="desc" value="<?php echo $desc;?>">
  <br><br>
  <input type="submit" name="submitAdd">
</form>

</body>
</html>