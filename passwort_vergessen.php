<!doctype html>
<html lang="de">
    <head>
        <title>Passwort zurücksetzen</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link rel="stylesheet" href="https://studentenportal.fhws.de/css/app.css?id=521f12362c5a59066c60">
        <style>
            .card{margin-top:10vw}
        </style>
    </head>
    <body>

        <!-- html Inhalt -->
        <main id="main">
            <div class="container mt-4">
                                
                <div class="mx-auto" style="max-width: 400px;">
                    <div class="card">
                        <div class="card-header">Passwort zurücksetzen</div>
                        <div class="card-body">
                            <form method="POST" action="passwort_vergessen.php" accept-charset="UTF-8">
                                <div class="form-group">
                                    <label>E-Mail:</label>
                                    <input class="form-control" required="true" name="EMAIL" type="text" placeholder="z.B. max.mustermann@student.fhws.de" pattern=".*@student.fhws.de">
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="mailAnfordern()">
                                    <i class="fas fa-fw fa-paper-plane"></i>
                                    E-Mail anfordern
                                </button>
                            </form>
                        </div>
                    </div>
        
                    <a href="log_in.php" class="btn btn-light rounded-pill">
                    <i class="fas fa-fw fa-arrow-circle-left"></i> Zurück</a>
                </div>

            </div>
        </main>

        <!-- Passwort generieren -->
        <?php
            function generatePassword ( $passwordlength = 10, $numNonAlpha = 2, $numNumberChars = 2, $useCapitalLetter = true )
            {     
             $numberChars = '0123456789';
             $specialChars = '!$%&=?*-:;.,+~@_';
             $secureChars = 'abcdefghjkmnpqrstuvwxyz';
             $stack = '';
             $stack = $secureChars;
             
             if ( $useCapitalLetter == true )
                 $stack .= strtoupper ( $secureChars );
                 
             $count = $passwordlength - $numNonAlpha - $numNumberChars;
             $temp = str_shuffle ( $stack );
             $stack = substr ( $temp , 0 , $count );
             
             if ( $numNonAlpha > 0 ) {
                 $temp = str_shuffle ( $specialChars );
                 $stack .= substr ( $temp , 0 , $numNonAlpha );
             }
                 
             if ( $numNumberChars > 0 ) {
                 $temp = str_shuffle ( $numberChars );
                 $stack .= substr ( $temp , 0 , $numNumberChars );
             }

             $stack = str_shuffle ( $stack );
            
             return $stack; 
            }
        ?>
        
        <!-- php fürs MAil Abschicken -->
        <?php
 
         if(isset($_POST['EMAIL']) && (null !== $_POST['EMAIL'])){
         $passwd = generatePassword ( 10 );

         $mailTo = $_POST['EMAIL'];
         $mailFrom = "From: Kai Schaefer <rolf.schillinger@fhws.de>\r\n";
         $mailFrom .= "Reply-To: rolf.schillinger@fhws.de\r\n";
         $mailFrom .= "Content-Type: text/html\r\n";
         $mailSubject = 'Dein neues Passwort';
         $returnPage = 'erfolgreich.php';
         $returnErrorPage = 'fehler.php';
         $mailText = "Hier ist dein neues Passwort:<br>" . $passwd ;
              
            if(get_magic_quotes_gpc()) {
            $mailtext = stripslashes($mailtext);}
          
         $mailSent = @mail($mailTo, $mailSubject, $mailText, "From: ".$mailFrom);
         if($mailSent == TRUE) {
            header("Location: " . $returnPage);
            echo "erfolg";
            $host = 'localhost';
            $user = 'root';
            $password = 'FIoLTAZo1pEar83N';
            $dbname = 'studenten';

            $dsn = 'mysql:host='. $host .';dbname='. $dbname;
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          $email = $_POST['EMAIL'];
          $user_password = $passwd;
          $sql = 'UPDATE studenten SET user_password = :user_password WHERE email = :email';
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['user_password' => $user_password, 'email' => $email]);
         }
         else {
            header("Location: " . $returnErrorPage);
         }
         exit();
         }
        ?>
        
    </body>
</html>
