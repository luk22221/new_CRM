<?php
session_start();
if (!isset($_SESSION['zalogowany'])) header('Location: /include/login_page.php');

include "config/connect.php";
include "include/nav.php";

if (isset($_SESSION['flag'])){
    $flag = $_SESSION['flag'];
    unset($_SESSION['flag']);
}
?>

<html lang="pl">
<html>
<head>
    <meta charset="UTF-8 general ci">
    <title>CRM-clients</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div style="margin-left: 2%; margin-right: 2%;">

<form action="save_in_database/find_clients.php" method="post">
    
    <div class="float inputclient" style="float:left;"><p class="textclient">Imię </p>
    <input type="text" name="find_name" value="<?php
    if (isset($_SESSION['fr_find_name']))
        {
            echo $_SESSION['fr_find_name'];
            unset($_SESSION['fr_find_name']);
        }?>"><br /></div>

    <div class="float inputclient" style="float:left;"><p class="textclient">Nazwisko </p>
    <input type="text" name="find_last_name" value="<?php
    if (isset($_SESSION['fr_find_last_name']))
        {
            echo $_SESSION['fr_find_last_name'];
            unset($_SESSION['fr_find_last_name']);
        }?>"><br /></div>

    <div class="float inputclient" style="float:left;"><p class="textclient">Telefon </p>
    <input type="text" name="find_phone" value="<?php
    if (isset($_SESSION['fr_find_phone']))
        {
            echo $_SESSION['fr_find_phone'];
            unset($_SESSION['fr_find_phone']);
        }?>"><br /></div>

    <div class="float inputclient" style="float:left;"> <p class="textclient">Email </p>
    <input type="text" name="find_email" value="<?php
    if (isset($_SESSION['fr_find_email']))
        {
            echo $_SESSION['fr_find_email'];
            unset($_SESSION['fr_find_email']);
        }?>"><br /></div>
        
    <div class="float inputclient" style="float:left;"><p class="textclient">Miasto</p>
    <input type="text" name="find_town" value="<?php
    if (isset($_SESSION['fr_find_town']))
        {
            echo $_SESSION['fr_find_town'];
            unset($_SESSION['fr_find_town']);
        }?>"><br /></div>
        
    
    <div class="float inputclient" style="float:left;"><input type="submit"  class="btn btn-dark" style="margin-top:20px;" value="Szukaj klienta" name="Submit" /></div>
</form><br /><br /><br />
<div class="float"  style="float: left;"><a href="new_client.php"><button type="button" class="btn btn-dark" style="color:black;">Dodaj nowego klienta</button></a></div>
<?php
if (isset($_SESSION['client_delete'])){
    echo '<div class="succes">'."usunięto klienta".'</div>';
    unset($_SESSION['client_delete']);
}
if (isset($_SESSION['client_added']))
    {?>
        <div class="succes">Klient został dodany do bazy</div>
    <?php
    unset($_SESSION['client_added']);
    }
if (isset($_SESSION['client_changed']))
    {?>
        <div class="succes">Dane klienta zostały zmienione</div>
    <?php
    unset($_SESSION['client_changed']);
    }
    //Usuwanie zmiennych pamiętających wartości wpisane do formularza
    if (isset($_SESSION['fr_id'])) unset($_SESSION['fr_id']);
    if (isset($_SESSION['fr_name'])) unset($_SESSION['fr_name']);
    if (isset($_SESSION['fr_last_name'])) unset($_SESSION['fr_last_name']);
    if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if (isset($_SESSION['fr_phone'])) unset($_SESSION['fr_phone']);
    if (isset($_SESSION['fr_postal_code'])) unset($_SESSION['fr_postal_code']);
    if (isset($_SESSION['fr_town'])) unset($_SESSION['fr_town']);
    if (isset($_SESSION['fr_street'])) unset($_SESSION['fr_street']);
    if (isset($_SESSION['fr_description'])) unset($_SESSION['fr_description']);
    
    //Usuwanie błędów 
    if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
    if (isset($_SESSION['e_last_name'])) unset($_SESSION['e_last_name']);
    if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if (isset($_SESSION['e_phone'])) unset($_SESSION['e_phone']);
    if (isset($_SESSION['e_postal_code'])) unset($_SESSION['e_postal_code']);

$records_per_page = 7;
if (1==1) {
    if(isset ($flag)) {
        $find_name = $_SESSION['find_name'];
        $find_last_name = $_SESSION['find_last_name'];
        $find_phone = $_SESSION['find_phone'];
        $find_email = $_SESSION['find_email'];
        $find_town = $_SESSION['find_town'];

        unset($_SESSION['find_name']); 
        unset($_SESSION['find_last_name']); 
        unset($_SESSION['find_email']); 
        unset($_SESSION['find_phone']); 
        unset($_SESSION['find_town']);

        if($result = $connect->query("SELECT * FROM clients WHERE name like '%$find_name%' and last_name like '%$find_last_name%' and email like '%$find_email%' and phone like '%$find_phone%' and town like '%$find_town%'"))
            {} else{
                exit("Błąd zapytania");
            }

    } else if($result = $connect->query("SELECT * FROM clients ")) {
    }else {
        exit("Błąd zapytania");
    }
    if(isset($result) && ($result->num_rows != 0)){
        $total_records = $result->num_rows;
        $total_pages = ceil($total_records / $records_per_page);

        if(isset($_GET['page']) && is_numeric($_GET['page'])){

            $show_page = $_GET['page'];

            if($show_page > 0 && $show_page <= $total_pages){
                $start = ($show_page-1) * $records_per_page;
                $end = $start + $records_per_page;
            } else {
                $start = 0;
                $end = $records_per_page;
            }
        } else {
            $start = 0;
            $end = $records_per_page;
        }
        echo '<div class="float" style="float: left;padding-right: 50px; font-size: 16px;"><p style="margin-top: 5px; margin-left: 10px;">  Zobacz stronę: ';
        for($i = 1; $i <= $total_pages; $i++){
            if(isset($_GET['page']) && $_GET['page'] == $i){
                echo $i . " ";
            } else {
                echo "<a href='clients.php?page=$i' style='color: grey;'>" . $i . "</a>";
            }
        }
        echo '</div></p><div class="clear"></div>';?>
        <table class="table table-striped " >
            <thead>
            <tr>
              <th scope="col">id</th>
              <th scope="col">Imię</th>
              <th scope="col">Nazwisko</th>
              <th scope="col">Numer telefonu</th>
              <th scope="col">Adres email</th>
              <th scope="col">Kod pocztowy</th>
              <th scope="col">Miasto</th>
              <th scope="col">Ulica</th>
              <th scope="col">Opis</th>
              </tr>
             </thead><?php
        for($i = $start; $i < $end; $i++){
            if($i == $total_records) {break;}

            $result->data_seek($i);
            $row = mysqli_fetch_array($result);

            echo '<thead class="thead-light">' . "<tr>";
            echo "<td>" . $row['id'];
            echo "<td>" . $row['name'];
            echo "<td>" . $row['last_name'];
            echo "<td>" . $row['phone'];
            echo "<td>" . $row['email'];
            echo "<td>" . $row['postal_code'];
            echo "<td>" . $row['town'];
            echo "<td>" . $row['street'];
            echo "<td>" . $row['description'];
            echo "<td>" . "<a href='clients_info.php?id=" . $row['id'] . "'>"?><button type="button" class="btn btn-secondary" style="color:black;">Edytuj</button></a><br /><?php
            echo "<td>" . "<a href='/save_in_database/delete_client.php?id=" . $row['id'] . "'>"?><button type="button" class="btn btn-dark" style="color:black;">Usuń</button></a><br /><?php
            echo "<tr>" . "</thead>";

        }
        echo "<table>";
        $result->free_result();
        $connect->close();



    } else {
        echo '<br /><br /><div class="error">'."brak rekordów".'</div>';
    }
}
?></div><?php
include "include/footer.php";
?>
</body>
</html>
