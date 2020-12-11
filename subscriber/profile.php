<?php 
include('/var/www/celestiallearning/utilities/dbconnect.php');
session_start(); 
require '/var/www/celestiallearning/vendor/autoload.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader('/var/www/celestiallearning/templates');
$twig = new Environment($loader);
?>

<html>
<head>
<title>Profile Page</title>
</head>
<body>

<h1> Profile Page</h1>

<?php
$profile = array();
$db = Database::getInstance();
$mysql = $db->getConnection();
$email = $_SESSION['email'];
$query = $mysql->prepare("SELECT * FROM SubscriberProfile where ID in(SELECT ID FROM Subscriber WHERE Email=?)");
$query->bind_param('s',$email);
$query->execute();
$result1 = $query->get_result();
$row_count1 = mysqli_num_rows($result1);


$query2 = $mysql->prepare("SELECT * FROM Subscriber WHERE Email=?");
$query2->bind_param('s',$email);
$query2->execute();
$result2 = $query2->get_result();
$rowcnt = mysqli_num_rows($result2);

foreach($result2 as $row)
{
    
}

foreach($result1 as $row1)
{

}

if($row_count1>0) //update
{
    $profile['ID'] = $row['ID'];
    $profile['Email'] = $email;
    $profile['FirstName'] = $row1['FirstName'];
    $profile['MiddleName'] = $row1['MiddleName'];
    $profile['LastName'] = $row1['LastName'];
    $profile['PhNum'] = $row1['PhNum'];
    $profile['LinkedInURL'] = $row1['LinkedInURL'];
    $profile['TwitterURL'] = $row1['TwitterURL'];
    $profile['HigherEducation'] = $row1['HigherEducation'];
    $profile['AreaOfInterest'] = $row1['AreaOfInterest'];
    echo $twig->render('subscriber/profile.html.twig',array('profile'=>$profile));
}
else
{
    $profile['ID'] = $row['ID'];
    $profile['Email'] = $email;
    echo $twig->render('subscriber/profile.html.twig',array('profile'=>$profile));
}
?>
</body>
</html>