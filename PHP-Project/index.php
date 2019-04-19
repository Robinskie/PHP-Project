<?php
    require_once("bootstrap.php");

    redirectIfLoggedOut();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>PROJECT</title>

    <!-- bullets wegdoen bij zoekresultaten -->
    <style  type="text/css" media="screen">
    ul  li{
    list-style-type:none;
}
</style>
</head>
<body>
    <?php include_once("includes/nav.inc.php");?>
    
    <h1>Homepage</h1>
    <div class="content">

    <!-- zoekformulier maken -->
    <form  method="post" action="search.php?go"  id="searchform"> 
	    <input  type="text" name="name"> 
        <input  type="submit" name="submit" value="Search"> 
    </form>
    <!-- einde formulier -->

    <!-- hoe werkt een zoekfunctie 
    De bezoeker geeft een waarde in en drukt op de knop,
    formulier gaat een go aan het einde van de query string toevoegen
    -->

    <!-- toevoegen van php voor zoekformulier -->
    <?php
    // is er op de submit knop geklikt
    if(isset($_POST['submit'])){ 

    // heeft de query string een go waarde 
    if(isset($_GET['go'])){

    // sql injectie tegengaan 
    // enkel hoofdletter of kleine letter invullen als eerste karakter
  
    if(preg_match("/^[  a-zA-Z]+/", 
    $_POST['name'])){ //  naam van het veld dat we willen checken
    $name=$_POST['name']; // verzamelen van zoek criteria

    // database connection met variabele db en ingebouwde functie myqsl_connect
    $db=mysql_connect  ("mysql:host=localhost;dbname=project", "root",  "root") or die ('I cannot connect to the database  because: ' . mysql_error());
  
    // moet kunnen zoeken op #tags en zoekwoorden 
    $sql="SELECT  ID, FirstName, LastName FROM Contacts WHERE FirstName LIKE '%" . $name .  "%' OR LastName LIKE '%" . $name ."%'";
    $result=mysql_query($sql);

    // loop door de zoekresultaten
    while($row=mysql_fetch_array($result)){
          $FirstName  =$row['FirstName'];
          $LastName=$row['LastName'];
          $ID=$row['ID'];
  
    // toon resultaten in een array
    echo "<ul>\n";
    echo "<li>" . "<a  href=\"search.php?id=$ID\">"   .$FirstName . " " . $LastName .  "</a></li>\n";
    echo "</ul>";
    }
    }
    else{
    echo  "<p> Vul het zoekveld in om een gebruiker te zoeken </p>";
  }
  }
  }
?>

    <h2><a href="uploadPhoto.php">Upload a picture</a></h2>
    </div>

    <?php
        //FEED
        $photoArray = Db::simpleFetchAll("SELECT * FROM photos ORDER BY uploadDate");
        foreach($photoArray as $photoRow):
            $photo = new Photo();
            $photo->setId($photoRow['id']);
            $photo->setName($photoRow['name']);
    ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
                    <p><?php echo $photo->getName(); ?></p>
                </a>
            </div>
        
    <?php endforeach ?>

</body>
</html>