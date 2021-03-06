<?php
    session_start();
    include "/var/www/celestiallearning/utilities/dbconnect.php";
    require '/var/www/celestiallearning/vendor/autoload.php';
    
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    $loader = new FilesystemLoader('/var/www/celestiallearning/templates');
    $twig = new Environment($loader);
    if($_SERVER['REQUEST_METHOD']==='GET')
    {
        echo $twig->render('author/login.html.twig');
    }
    else if(isset($_POST['submit']))
    {

        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT LoginPassword FROM Author WHERE Email = ?");         ///TABLE NAME CHNAGE KARNA HAI
        $stmt->bind_param("s", $email);
        $email = $_POST['email'];
        $stmt->execute();
        $result = $stmt->get_result();
        if($result)
        {
            $row_count = mysqli_num_rows($result);
            
            if ($row_count == 1)
            {
                $row = $result->fetch_assoc();
                $hash = $row['LoginPassword'];
                $password = $_POST['password'];
                if(password_verify($password, $hash))
                {
                    $_SESSION['email'] = $email;
                    $stmt = $conn->prepare("SELECT AccountStatus FROM Author WHERE Email = ?");
                    $stmt->bind_param("s", $email);
                    $email = $_POST['email'];
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result)
                    {
                        $row_count2 = mysqli_num_rows($result);
                        $status = $result->fetch_assoc();
                        $status1 = $status['AccountStatus'];
                        
                        if($status1=="Active")
                        {
                            header('Location: dashboard.php');
                           
                        }
                        else
                        {
                            echo "Please activate your account! An activation link has been sent to your registered email address.";
                        }
                    }
                    
                    
                }
                else
                {
                    echo $twig->render('author/login.html.twig', ['invalid_login' => "Incorrect username or password."]);
                }
            }
            else
            {
                echo $twig->render('author/login.html.twig', ['invalid_login' => "Incorrect username or password."]);
            }
        }

    }
?>
