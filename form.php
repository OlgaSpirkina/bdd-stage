<?php
// Start the session
session_start();
?>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/styles/main.css" type="text/css" media="screen" />
    <title>recherche de stage</title>
  </head>
  <body data-theme="light">
    <header>
      <h1>Recherche de stage Juillet 2021</h1>
      <?php
      $currentTime = date("mm/dd/yyyy");
      setlocale(LC_ALL, "fr_FR.utf_8", 'fra');
      ?>
      <small>Aujourd'hui nous somme <em style="color:#5bbbcb; font-weight:bold"><?php echo ucwords(strftime("%A %e %B %Y")) ?></em></small>
    </header>
    <div class="container">
      <form method="post" action="form.php">
        <fieldset>
         <legend><strong>Entreprises:</strong></legend>
         <p>
           <label for="nom">Nom d'entreprise:</label>
           <input type="text" id="nom" name="nom">
         </p>
         <p>
           <label for="theDate">Date d'envoie de la candidature:</label>
           <input type="date" id="theDate" name="theDate">
         </p>
         <p>
           <label for="reponse">Réponse:</label>
           <input type="text" id="reponse" name="reponse">
         </p>
         <p>
           <label for="test">Test technique:</label>
           <input type="text" id="test" name="test">
         </p>
         <p>
           <label for="entretien">Entretien:</label>
           <input type="text" id="entretien" name="entretien">
         </p>
         <p>
           <label for="thePost">Post:</label>
           <input type="text" id="thePost" name="thePost">
         </p>
         <button type="submit">Mettre à jour</button>
        </fieldset>
        <?php
          if (empty($_POST['nom']) || empty($_POST['theDate']) || empty($_POST['reponse']) || empty($_POST['test']) || empty($_POST['entretien']) || empty($_POST['thePost'])){
            ?>
            <div>
              <p>Renseignez les champs indiqués</p>
            </div>
      </form>
  <?php }else{
    try
    {
      $answer = new PDO('mysql:host=database;dbname=recherche_de_stage;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
            die('Erreur : '.$e->getMessage());
    }
    $req = $answer->prepare('INSERT INTO entreprises(nom, theDate, reponse, test, entretien, thePost) VALUES (?, ?, ?, ?, ?, ?)');
    $req->execute(array($_POST['nom'], $_POST['theDate'], $_POST['reponse'], $_POST['test'], $_POST['entretien'], $_POST['thePost']));

  }
  ?>
    <table>
      <thead>
        <th>ID</th>
        <th>Nom</th>
        <th>Date</th>
        <th>Réponse</th>
        <th>Test</th>
        <th>Entretien</th>
        <th>Post</th>
        <th>Temps passé</th>
      </thead>
      <tbody>
    <?php
    $answer = new PDO('mysql:host=database;dbname=recherche_de_stage; charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $page = (!empty(htmlspecialchars($_GET['page'])) ? $_GET['page'] : 1);
    $limite = 7;
    $resultFoundRows = $answer->query('SELECT count(id) FROM `entreprises`');
    $nombredElementsTotal = $resultFoundRows->fetchColumn();
    $nombreDePages = ceil($nombredElementsTotal / $limite);
    $debut = ($page - 1) * $limite;
    // $query = 'SELECT nom, theDate, reponse, test, entretien, thePost FROM entreprises ORDER BY id LIMIT :limite';
    $query = 'SELECT * FROM `entreprises` LIMIT :limite OFFSET :debut';
    $query = $answer->prepare($query);
    $query->bindValue('limite', $limite, PDO::PARAM_INT);
    $query->bindValue('debut', $debut, PDO::PARAM_INT);
    $query->execute();

    // $reponse = $answer->prepare('SELECT nom, theDate, reponse, test, entretien, thePost FROM entreprises ORDER BY id LIMIT 0, 7') or die(print_r($answer->errorInfo()));
    // $reponse->execute(array($_GET['page'], $start_comment));
    $counter = 1;

    while ($donnees = $query->fetch()) {
    ?>
      <tr>
        <td>
          <?php echo $counter++ ?>
        </td>
        <td>
          <strong><?php echo htmlspecialchars($donnees['nom'])?></strong>
        </td>
        <td>
          <?php echo htmlspecialchars($donnees['theDate'])?>
        </td>
        <td>
          <?php echo htmlspecialchars($donnees['reponse'])?>
        </td>
        <td>
          <?php echo htmlspecialchars($donnees['test'])?>
        </td>
        <td>
          <?php echo htmlspecialchars($donnees['entretien'])?>
        </td>
        <td>
          <?php echo htmlspecialchars($donnees['thePost'])?>
        </td>
        <td>
          <?php $date_expire = $donnees['theDate'];
                $date = new DateTime($date_expire);
                $now = new DateTime();

                echo $date->diff($now)->format("%R%a days") ?>
        </td>
      </tr>

    <?php
    }

    ?>
    </tbody>
    </table>
    </div>
    <footer>
      <p><em>Page: </em>
        <?php
        for ($i = 1; $i <= $nombreDePages; $i++):
            ?><a href="?page=<?php echo $i; ?>"><em><?php echo $i; ?></em></a> <?php
        endfor;
         ?>
      </p>
    </footer>
  </body>
</html>
