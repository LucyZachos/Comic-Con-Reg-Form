<?php

//checks the the registration form has been submitted
if(isset($_POST['register'])){

    //connects to the MySQL database
    $host = "localhost";
    $username = "root";
    $password = "";
    $db = "comic_con";
    $link = mysqli_connect($host, $username, $password, $db);

    //takes the user input from the registration form and escapses any special characters
    $first_name = mysqli_real_escape_string($link, $_REQUEST['first_name']);
    $last_name = mysqli_real_escape_string($link, $_REQUEST['last_name']);
    $cellphone = mysqli_real_escape_string($link, $_REQUEST['cellphone']);
    $email = mysqli_real_escape_string($link, $_REQUEST['email']);
    $passwrd = mysqli_real_escape_string($link, $_REQUEST['passwrd']);
    $fav_comic = mysqli_real_escape_string($link, $_REQUEST['fav_comic']);

    //if the connection to the database fails display an error message and exits the function
    if(!$link){
        die("Database connection failed: " . mysqli_connect_error()) ;
    }

    //SQL statement to insert user data into the table
    $sql = "INSERT IGNORE INTO users (first_name, last_name, cellphone, email, passwrd, fav_comic)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);

    //Binds the parameters
    mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $cellphone, $email, $passwrd, $fav_comic);
    mysqli_stmt_execute($stmt);

    //welcome alert
    if(mysqli_affected_rows($link) > 0){
        echo "<script> alert('Registration successful. Welcome " . $first_name . " " . $last_name . "!')</script>";

        $to = $email;
        $from = 'kimikoentertainmentza@gmail.com';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: Kimiko Entertainment<kimikoentertainmentza@gmail.com>\n"."X-Mailer: PHP";
        $headers .= 'From: '.$from."\r\n".  'X-Mailer: PHP/' . phpversion();

        //welcome email to user
        $subject = "Hi " . $first_name . ", welcome to Comic Con online!";
        $message = 
        "<html>
            <body>
                <centre>
                <a href='http://localhost/Comicon/survey.html'><img src='https://alltimelines.com/wp-content/uploads/2019/10/amalgam-banner.jpg' height='400px'></a>
                <h1 style='font-size: 40px; color: black;'><strong>Welcome $first_name!</strong></h1><br>
                <p>Thank you for joining our site and welcome to the vast world of Comic Con!</p>
                <p>We are so happy to have you join us!</p><br>
                <p>Kind Regards,</p>
                <h3>Kimiko Entertainment</h3>
                <p>P.S.  If you have a chance, please could you answer this survey: <br>
                <br><a href='http://localhost/Comicon/survey.html'><button style='background-color: #023e8a; color:white; padding: 10px;'>Take Survey</button</a><br>
            </body>
        </html>"; 


        mail($to, $subject, $message, $headers);

        //Redirect the user to the welcome page after registering
        header('Refresh: 0.2; url=welcome.html'); 

    }else{
        echo "<script> alert('This email already exists. Please try again!')</script>";
        //Redirect the user to the register page after registering
        header('Refresh: 0.2; url=register.html'); 
        exit();
    }
    //close the MySQL connection
    mysqli_close($link);

}
?>