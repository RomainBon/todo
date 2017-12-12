<?php
// les identifiant user et password seront a modifié
define( 'DB_NAME', 'TODO' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_TABLE', 'Liste' );

// connexion à Mysql sans base de données
$pdo = new PDO('mysql:host='.DB_HOST, DB_USER, DB_PASSWORD);

// on teste avant si elle existe ou non (par sécurité)
$requete = "CREATE DATABASE IF NOT EXISTS `".DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";

// on prépare et on exécute la requête
$pdo->prepare($requete)->execute();

// connexion à la bdd
$connexion = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);

// on vérifie que la connexion est bonne
if($connexion)
{
	// on créer la requête
	$requete = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".DB_TABLE."` (
				`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`Action` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
				) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

	// on prépare et on exécute la requête
	$connexion->prepare($requete)->execute();

	//on traite les datas
	if ($_POST['del']!=NULL)
	{
		$requete = "DELETE FROM ".DB_TABLE." WHERE ID=".$_POST['del'];
		$connexion->prepare($requete)->execute();
	}
	elseif($_POST['action']!=NULL)
	{
		$requete = "INSERT INTO ".DB_TABLE."(Action) VALUES('".$_POST['action']."')";
		$connexion->prepare($requete)->execute();
	}
}
?>
<html lang="fr">
	<head>
  		<meta charset="utf-8">
		    <title>To Do List</title>
	  	<meta name="description" content="Tâche a Faire:">
	  	<meta name="author" content="MRB">
      <link rel="stylesheet" href="style.css">
	  	<h1>To Do Liste : </h1>
	</head>

	<body>
	<div id='cadre'>
    <form  method="post" action="todolist.php">

        <?php
					$cont =1;
		      $results=$connexion->query('SELECT * FROM '.DB_TABLE);
					$results->setFetchMode(PDO::FETCH_OBJ);
          while ($result = $results->fetch())
          {
            echo "<li><form method='post' action='todolist.php'><input id='img' type='image' name='supp' src='empty--recycle-bin-icon-27617.png' />	".$cont." : ".$result->Action." <input type='hidden' name='del' value='".$result->ID."' /></form></li>";
						$cont++;
          }
		     ?>
    </form>

    <form method="post" action="todolist.php">
      <label>Ajouter une Tâche :</label><input type="text" name="action" />
      <input type="submit" value="Ajouter" />
    </form>
</div>
	</body>
</html>
