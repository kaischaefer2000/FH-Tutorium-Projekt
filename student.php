<html>
    <head lang='de'>
        <title>Student Benutzeroberfläche</title>
        <meta charset='UTF-8'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <style>
            #logo{width:15vw; height:auto; padding:1vw}
            #heading{text-align:center; font-size: 3vw; margin-top: 1.2vw;}
            #profile{font-size: 1.8vw; margin:1.7vw 0vw 1vw 1vw; position:absolute;}
            #profile_icon{margin: 1.5vw 1vw 1vw 19vw; position:absolute;}
            #header{box-shadow: 0px 0px 5px grey;}
            #content{background-color: rgb(0,0,0,0.05); height:87.7%;}
            .btn_speichern{background-color:green; border-radius:5px; color:white; border:none; }
            .eigenschaften{margin-bottom:0px}
            #aendern{margin:-2vw 0vw 0vw 4vw; font-size:1.2vw}
            #configinc{margin:-2vw 0vw 0vw 0vw}
            #txt{margin:2vw 4vw 0vw 4vw; font-size:1.4vw}
            h2{font-size:1.9vw}
            #anleitung{font-size:1.2vw}
            input{border-radius:4px;border: 1px solid gainsboro; height:2.2vw}
        </style>
    </head>
    <body>
        <?php
            session_start();
            $iststudent = $_SESSION['studentenprufung'];
            if($iststudent !== "3"){
                header('location: nichtberechtigt.php');
                exit(1);
            }
        ?>
        <div class="container-fluid">
            <!-- Header -->
            <div class="row" id="header">
                <div class="col-md-3 col-sm-3">
                    <img src="logo.png" id="logo">
                </div>
                
                <div class="col-md-6 col-sm-6">
                    <h1 id="heading">Benutzeroberfläche</h1>
                </div>

                <div class="col-md-3 col-sm-3">
                    <p id="profile">
                    <?php  
                        $name = $_SESSION['useremail'];
                        echo $name;
                    ?>
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" id="profile_icon" width="4vw" height="4vw" viewBox="0 0 40 40"><path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                </div>
                
            </div>

            <!-- php zu Passwort ändern -->
            <?php   
                $host = 'localhost';
                $user = 'root';
                $password = 'FIoLTAZo1pEar83N';
                $dbname = 'studenten';
                $dsn = 'mysql:host='. $host .';dbname='. $dbname;
                $pdo = new PDO($dsn, $user, $password);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                $nummer = 0;
                if(isset($_POST["aktuellespasswort"]) && (null !== $_POST["aktuellespasswort"])){
                $search = $name;
                $sql = 'SELECT * FROM studenten WHERE email LIKE ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$search]);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    if($row['email'] == null){
                        echo "Ein User mit dieser E-Mail existiert nicht";
                    }
                    else{
                        if( password_verify($_POST["aktuellespasswort"], $row['user_password'])){
                            if(isset($_POST["neuespasswort"]) && (null !== $_POST["neuespasswort"])){
                                if($_POST['neuespasswort'] !== $_POST['neuespasswortbest']){
                                    $nummer = 5;
                                }
                                else{
                                    $email = $name;
                                    $pass_wort = $_POST['neuespasswort'];
                                    $hash = password_hash($pass_wort, PASSWORD_DEFAULT);
                                    $user_password = $hash;
                                    
                                    $sql = 'UPDATE studenten SET user_password = :user_password WHERE email = :email';
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['user_password' => $user_password, 'email' => $email]);
                                    $nummer ++;
                                }
                            }
                        }
                        else{
                            echo '<script type="text/javascript">
                            alert("Das eingegebene Passwort ist falsch");
                            </script>';
                        }
                    }}}
            ?>

            <!-- Einleitungssatz -->
            <div class="row">
                <div class="col-md-12">
                    <p id="txt">Sie haben sich erfolgreich angemeldet. Sie können jetzt Ihre Entwicklungsumgebung einrichten oder Ihr Passwort ändern, wenn Sie möchten.</p>
                </div> 
            </div>

            <hr>
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <!-- formular für passwort ändern -->
                    <div id="aendern">
                        <h2><b>Passwort ändern</b></h2>

                        <form class='formular' method="post" action="student.php">
                            <p class="eigenschaften">aktuelles Passwort:</p>
                            <input type='text' name='aktuellespasswort' placeholder='aktuelles Passwort' maxlength="15" minlength="6" required>
                            <br><br>
                            <p class="eigenschaften">neues Passwort:</p>
                            <input type='text' name='neuespasswort' placeholder='neues Passwort' maxlength="15" minlength="6" required>
                            <br><br>
                            <p class="eigenschaften">neues Passwort bestätigen:</p>
                            <input type='text' name='neuespasswortbest' placeholder='neues Passwort bestätigen' maxlength="15" minlength="6" required>
                            <br><br>
                            <button type='submit' name="buttn" class="btn_speichern">Speichern</button>
                            <br><br>
                            <?php
                                if($nummer == 1){
                                    echo "Du hast dein Passwort erfolgreich geändert  " . 
                                    "<svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' viewBox='0 -2 24 24'><path d='M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z'/></svg>";
                                }
                                if($nummer == 5){
                                    echo "Die eingegebenen neuen Passwörter sind nicht identisch!";
                                }
                            ?>
                        </form>
                     </div>
                </div>

                <!-- config inc datei -->
                <div class="col-md-7 col-sm-7">
                    <div id="configinc">
                        <h2><b>Visual Studio Code einrichten</b></h2>
                        <ol id="anleitung">
                            <li>Laden Sie sich das Programm "Visual Studio Code" kostenlos runter.<br>
                            Benutzen Sie dazu diesen Link: <a href="https://code.visualstudio.com/download">https://code.visualstudio.com/download</a></li>
                            <li>Kopieren Sie diesen JSON Code.<br>
                                <code>{<br>"name": "FHWS Hosting",<br>"host": "user107.wp2019.fhws-webprog.de",<br>"protocol": "sftp",<br>"port": 22,<br>"username": "user107",<br>"password": "mcweir2nc",<br>"remotePath": "/home/user107/htdocs/",<br>"uploadOnSave": true<br>}
                                </code>
                            </li>
                            <li>Fügen Sie den Code im SFTP Plugin von Visual Studio Code ein. Klicken Sie dazu auf das unterste der 5 Symbole ("Extensions") in der linken Leiste und suchen sie "SFTP".</li>
                            <li>(Sollten Sie eine andere IDE verwenden wollen, finden Sie alle dazu nötigen Daten ebenfalls in dieser JSON Datei.)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> 

        <!-- Session löschen -->
        <?php
            $session_timeout = 30; // 1800 Sek./60 Sek. = 30 Minuten
            if (!isset($_SESSION['last_visit'])) {
              $_SESSION['last_visit'] = time();
            }
            if((time() - $_SESSION['last_visit']) > $session_timeout) {
              session_destroy();
              echo "<script language='javascript'>document.location.reload();</script>";
            }
            $_SESSION['last_visit'] = time();
        ?>
    </body>
</html>
