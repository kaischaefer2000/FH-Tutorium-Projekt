<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link rel="stylesheet" href="https://studentenportal.fhws.de/css/app.css?id=521f12362c5a59066c60">
                <title>Log In</title>
    </head>

    <body>
    <?php session_start(); ?>
        

    <main id="main">
    <div class="container mt-4">
                                
    <div class="mx-auto mt-5" style="max-width: 400px;">
        <!--header-->
        <header class="mb-4">
            <img src="https://studentenportal.fhws.de/img/logo.svg" alt="" width="100" style="padding-left: 1px;">
            <h3 class="mt-1" style="font-weight:normal">Mail Verteiler</h3>
        </header>

        
        <div class="card">
            <div class="card-body">
                <form method="POST" action="log_in.php" accept-charset="UTF-8" class="formular">
                    <!-- <input name="_token" type="hidden" value="Ca3dsumBPuWvCa7tJ13oK6khcG6HVhfY1X9bxQhK"> -->
                    <div class="form-group">
                        <label>E-Mail</label>
                        <input class="form-control" tabindex="1" required="true" placeholder="z.B. max.mustermann@student.fhws.de" autofocus="true" name="Email" type="text">
                    </div>
                    <div class="form-group">
                        <label class="d-flex">Passwort
                            <a class="ml-auto" href="passwort_vergessen.php">Passwort vergessen?</a>
                        </label>
                    <div class="input-group">
                            <input id="password" class="form-control" tabindex="2" required="true" name="password" type="password" placeholder="6 bis 15 zeichen">
                    </div>
            </div>

                    <button type="submit" class="btn btn-primary" tabindex="4">Anmelden</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Log In daten prüfen-->
    <?php
        $host = 'localhost';
        $user = 'root';
        $password = 'FIoLTAZo1pEar83N';
        $dbname = 'studenten';
        $dsn = 'mysql:host='. $host .';dbname='. $dbname;
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        $prüfzahl = 0;
        // SEARCH DATA
        if(isset($_POST["Email"]) && (null !== $_POST["Email"])){
        $search = $_POST['Email'];
        $sql = 'SELECT * FROM studenten WHERE email LIKE ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search]);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($row['email'] == null){
                echo "Ein User mit dieser E-Mail existiert nicht";
            }
            else{
                $prüfzahl ++;
                if( password_verify($_POST["password"], $row['user_password'])){
                    echo "alles hat geklappt";
                    $prüfzahl ++;
                    $_SESSION['useremail'] = $_POST["Email"];
                    if($prüfzahl == 2){
                        if($_POST["Email"] == "rolf.schillinger@fhws.de" ){
                            $store = $row['administrator'];
                            $_SESSION['adminprufung'] = 1;
                            $_SESSION['studentenprufung'] = "0";
                            header('location: admin.php');
                            exit(1);
                            echo  $row['administrator'];
                            var_dump($store);
                            var_dump($row['administrator']);
                        }
                        else{
                            $_SESSION['adminprufung'] = 0;
                            $_SESSION['studentenprufung'] = "3";
                            header('location: student.php');
                            exit(1);
                        }
                    }
                }
                else{
                    echo '<script type="text/javascript">
                    alert("Das eingegebene Passwort ist falsch");
                    </script>';
                }
            }}
            }
        die();
    ?>




            </div>
        </main>
        
    </body>
</html>
