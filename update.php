<?php
	$autoloader = join(DIRECTORY_SEPARATOR,[__DIR__,'vendor','autoload.php']);
	require $autoloader;
	//require('index.php');

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
$docId = $_POST["productID"];
echo $docId;
// $name = test_input($_POST["productName"]);
// $name = test_input($_POST["name"]);
// $price = test_input($_POST["price"]);
// $desc = test_input($_POST["desc"]);


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Update your project here!</h2>

<form action="index.php" method="post">
  <input type="hidden" name="productID" value="<?php echo $docId ?>">
  Product Name: <input type="text" name="name" value="<?php echo $name;?>">
  <br><br>
  Price: <input type="text" name="price" value="<?php echo $price;?>">
  <br><br>
  Description: <input type="text" name="desc" value="<?php echo $desc;?>">
  <br><br>    
  <input type="submit" name="updateButton">
</form>	

</body>
</html>