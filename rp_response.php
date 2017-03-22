<html>
  <head>
    <meta http-equiv="Content-Language" content="cs">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">                                    

    <link rel="stylesheet" type="text/css" href="rp_table.theme.silver.css">
    <link rel="stylesheet" type="text/css" href="comments.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>Připomínky</title> 
    <style>

    </style>
  </head>
  <body>  

    <?php
// define variables and set to empty values
    $emailErr = $textErr = "";
    $text = $comment = "";
    $email = "@";
    $file = "rp_response_cont.html";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (empty($_POST["email"])) {
        $emailErr = "Je potřeba vyplnit email";
      } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Nesprávný formát emailové adresy";
        }
      }

      if (empty($_POST["comment"])) {
        $textErr = "Je potřeba vyplnit zprávu";
      } else {
        $text = test_input($_POST["comment"]);
      }

      $err = $emailErr . $textErr;
      if ($email != "" && $text != "" && $err == "") {
        $timeStamp = time();
        $comment = '<!--' . $timeStamp . '--><div class="comment">' .
                '<h5>' . date('d.m.Y H:i') . ', ' . $email . '</h5>' .
                '<p>' . $text . '</p>' .
                '</div>' . PHP_EOL;
        file_put_contents($file, $comment, FILE_APPEND);
        $name = $email = $text = $comment = "";
      }
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    ?>

    <h3>Připomínky</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
      <label>E-mail:</label><span class="error">* <?php echo $emailErr; ?></span>
        <input type="text" name="email" value="<?php echo $email; ?>">
        
      <label>Zpráva:</label><span class="error">* <?php echo $textErr; ?></span>
        <textarea name="comment" rows="2"><?php echo $text; ?></textarea>
      <input type="submit" name="odeslat" value="Odeslat">  
    </form>

    <h4>Komentáře</h4>
    <div>
      <?php
      require_once 'rp_response_cont.html';
      ?>
    </div>

  </body>
</html>