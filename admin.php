<html>
    <head lang='de'>
        <title>Admin User Verwaltung</title>
        <meta charset='UTF-8'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <style>
            #logo{width:15vw; height:auto; padding:1vw}
            #heading{text-align:center; font-size: 3vw; margin-top: 1.2vw;}
            #profile{font-size: 1.8vw; margin:1.7vw 0vw 1vw 7vw; position:absolute;}
            #profile_icon{margin: 1.5vw 1vw 1vw 19vw; position:absolute;}
            #header{box-shadow: 0px 0px 5px grey;}
            #content{background-color: rgb(0,0,0,0.05); height:87.7%;}
            #tabelle{width:98%; margin: 2.5vw 0vw 0.5vw 0.7vw; border-collapse: separate;}
            th{width:25%; border-bottom: 1px solid #ddd; }
            .spalte{border-bottom: 1px solid #ddd;}
            #box{overflow: auto; width:100%; height:100%}

            .tab {overflow: hidden; margin-top:0.5vw }
            .tab button {background-color: inherit; height:2vw;float: left; border:1px solid #ccc; outline: none; cursor: pointer; padding: 4px 16px; transition: 0.3s;border-radius: 20px 20px 0px 0px;}
            .tab button:hover {background-color: #ddd;}
            .tab button.active {background-color: #ccc;}
            .tabcontent {display: none;padding: 6px 2vw; border: 1px solid #ccc; border-top: none; height:93%}

            .btn_speichern{background-color:green; border-radius:5px; color:white; border:none; }
            #btn_löschen{background-color:red; border-radius:5px; color:white; border:none; }
            .eigenschaften{margin-bottom:0px}

            #erfolgreich{display:none}
            #grau{background-color: lightgrey; border-radius:5px}
            #erfolgreich p{text-align:center; font-size:2vw;}
            
        </style>
    </head>
    <body>
        <?php
            session_start();
            $admin = $_SESSION['adminprufung'];
            if($admin != 1){
                header('location: nichtberechtigt.php');
                exit(1);
            }
            $aktor = 0;
        ?>

        <div class="container-fluid">
        <!-- fade erfolgreich -->
        <div class="row" id="erfolgreich">
            <div class="col-md-2 col-sm-2"></div>
            <div class="col-md-8 col-sm-8" id="grau">
                <p> Der vorgang war erfolgreich :)</p>
            </div>
            <div class="col-md-2 col-sm-2"></div>
        </div>

            <!-- Header -->
            <div class="row" id="header">
                <div class="col-md-3 col-sm-3">
                    <img src="logo.png" id="logo">
                </div>
                
                <div class="col-md-6 col-sm-6">
                    <h1 id="heading">User Verwaltung</h1>
                </div>

                <div class="col-md-3 col-sm-3">
                    <p id="profile">Administrator</p>
                    <svg xmlns="http://www.w3.org/2000/svg" id="profile_icon" width="4vw" height="4vw" viewBox="0 0 40 40"><path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                </div>
                
            </div>


            <div class="row" id="content">
            <!-- php zu User hinzufügen-->
                    <?php
                        
                        $host = 'localhost';
                        $user = 'root';
                        $password = 'FIoLTAZo1pEar83N';
                        $dbname = 'studenten';

                        $dsn = 'mysql:host='. $host .';dbname='. $dbname;
                        $pdo = new PDO($dsn, $user, $password);
                        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        
                        if(isset($_POST["e_mail"]) && (null !== $_POST["e_mail"])){
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $email = $_POST["e_mail"];
                                $first_name = $_POST["vorname"];
                                $last_name = $_POST["nachname"];
                                $passwort = $_POST["passwort"];
                                $hash = password_hash($passwort, PASSWORD_DEFAULT);
                                $user_password = $hash;
                            }
                        }

                        if(isset($_POST["e_mail"]) && (null !== $_POST["e_mail"])){
                            $suchen = $_POST['e_mail'];
                            $sql = 'SELECT * FROM studenten WHERE email LIKE ?';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$suchen]);
                            if($stmt->rowCount() == 0 ){
                                    $sql = 'INSERT INTO studenten(email, first_name, last_name, user_password) VALUES(:email, :first_name, :last_name, :user_password)';
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['email' => $email, 'first_name' => $first_name, 'last_name' => $last_name, 'user_password' => $user_password]);
                                    unset($_POST["e_mail"]);
                            }
                            else{
                                echo '<script type="text/javascript">
                                        alert("Es gibt bereits einen User mit dieser E-Mail!");
                                      </script>';
                            }
                        }                            
                    ?>

            <!-- php zu User löschen-->
                    <?php
                      if(isset($_POST["e-mail"]) && (null !== $_POST["e-mail"])){
                        $zahl = 0;
                        if(null !== $_POST["e-mail"]){
                            $search = $_POST["e-mail"];
                            $sql = 'SELECT * FROM studenten WHERE email LIKE ?';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$search]);
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                if($row['email'] !== null){
                                    $email = $_POST["e-mail"];
                                    $sql = 'DELETE FROM studenten WHERE email = :email';
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['email' => $email]);
                                    $zahl = 2;
                                }
                            }
                            if($zahl == 0){
                                echo '<script type="text/javascript">
                                      alert("Es gibt keinen User mit dieser E-Mail!");
                                    </script>';
                            }
                        }
                      }
                    ?>

            <!-- php zu User Passwort ändern-->
                    <?php
                        if(isset($_POST["mail"]) && (null !== $_POST["mail"])){
                            if($_POST['neues_passwort'] !== $_POST['neues_passwort_best']){
                                $aktor = 1;
                            }
                            else{
                            $email = $_POST["mail"];
                            $pass_wort = $_POST['neues_passwort'];
                            $hash = password_hash($pass_wort, PASSWORD_DEFAULT);
                            $user_password = $hash;

                            $sql = 'UPDATE studenten SET user_password = :user_password WHERE email = :email';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['user_password' => $user_password, 'email' => $email]);
                        }}
                    ?>

                <div class="col-md-6 col-sm-6" id="left">
                    <!-- Datenbanken Tabelle -->
                  <div id="box">
                    <table id="tabelle">
                        <tr>
                            <th>E-Mail</th><th>First Name</th><th>Last Name</th><th>Passwort</th>
                        </tr>
                    <?php
                        $stmt = $pdo->query('SELECT * FROM studenten');
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            echo "<tr class='spalte'><td class='spalte'>" 
                            . $row['email'] . "</td><td class='spalte'>" 
                            . $row['first_name'] . "</td><td class='spalte'>" 
                            . $row['last_name'] . "</td><td class='spalte'>" 
                            . $row['user_password'] . "</td></tr>";
                            }
                    ?>
                  
                    </table>
                    </div>
                </div>
                
                <?php 
                    unset($email);unset($first_name); unset($last_name); unset($user_password);
                ?>
                

                <div class="col-md-6 col-sm-6" id="right">
                    
                    <!-- Tabs -->
                    <div class="tab">
                        <button class="tablinks" id="defaultOpen" onclick="openTab(event, 'add')">User hinzufügen</button>
                        <button class="tablinks" onclick="openTab(event, 'delete')">User löschen</button>
                        <button class="tablinks" onclick="openTab(event, 'change')">Passwort ändern</button>
                    </div>
                    
                    <!-- Neuen User hinzufügen -->
                    <div id="add" class="tabcontent" class="active">
                        <h3>Neuen User hinzufügen</h3>
                        <br>
                        
                        <form id='formular_eins' method="post" action="admin.php">
                            <p class="eigenschaften">E-Mail:</p>
                            <input type='text' name='e_mail' placeholder='E-Mail' required maxlength="50" minlength="7" value="@student.fhws.de" pattern=".*@student.fhws.de">
                            <br><br>
                            <p class="eigenschaften">Vorname:</p>
                            <input type='text' name='vorname' placeholder='Vorname' required>
                            <br><br>
                            <p class="eigenschaften">Nachname:<p>
                            <input type='text' name='nachname' placeholder='Nachname' required>
                            <br><br>
                            <p class="eigenschaften">Passwort:</p>
                            <input type='text' name='passwort' placeholder='Passwort' maxlength="15" minlength="6" required>
                            <br><br><br>
                            <button type='submit' name="btn" class="btn_speichern" onclick="leeren()">Speichern</button>
                        </form>    
                    </div>
                    
                 
                <!-- User löschen -->
                 <div id="delete" class="tabcontent">
                    <h3>User löschen</h3>
                    <br>
                    <form class='formular' method="post" action="admin.php">
                        <p class="eigenschaften">E-Mail:</p>
                        <input type='text' name='e-mail' placeholder='E-Mail' required maxlength="50" minlength="7" value="@student.fhws.de" pattern=".*@student.fhws.de">
                        <br><br><br>
                        <button type='submit' name="button" id="btn_löschen">Löschen</button>
                    </form>  
                 </div>

                 <!-- Passwort ändern -->
                 <div id="change" class="tabcontent">
                    <h3>Passwort ändern</h3>
                    <br>

                    <form class='formular' method="post" action="admin.php">
                        <p class="eigenschaften">E-Mail:</p>
                        <input type='text' name='mail' placeholder='E-Mail' required maxlength="50" minlength="7" value="@student.fhws.de" pattern=".*@student.fhws.de">
                        <br><br>
                        <p class="eigenschaften">neues Passwort:</p>
                        <input type='text' name='neues_passwort' placeholder='neues Passwort' maxlength="15" minlength="6" required>
                        <br><br>
                        <p class="eigenschaften">neues Passwort bestätigen:</p>
                        <input type='text' name='neues_passwort_best' placeholder='neues Passwort bestätigen' maxlength="15" minlength="6" required>
                        <br><br><br>
                        <button type='submit' name="butn" class="btn_speichern">Speichern</button>
                    </form>
                 </div>

                <!-- JS für Tabs -->
                <script>
                    document.getElementById("defaultOpen").click();

                    function openTab(evt, action) {
                        var i, tabcontent, tablinks;

                    tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                        }

                    tablinks = document.getElementsByClassName("tablinks");
                        for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                        }

                    document.getElementById(action).style.display = "block";
                    evt.currentTarget.className += " active";
                    }
                    </script>
                </div>
            </div>
        </div>

        <!-- Session löschen -->
        <?php
            $session_timeout = 30; 
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
