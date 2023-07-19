<?php
if (!empty($_POST['pass'])) {
    require_once("env.php");

    try {
        $pdo = new PDO(_DSN, _DB_USER, _DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $Exception) {
        die('エラー :' . $Exception->getMessage());
    }

    try {
        $pdo->beginTransaction();
        $sql = "UPDATE  main
                  SET 
                    text   = :text
                  WHERE pass = :pass";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':pass',   $_POST['pass'],   PDO::PARAM_STR);
        $stmh->bindValue(':text',   $_POST['text'],   PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "エラー：" . $Exception->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>webmemo</title>
</head>

<body>
    <form action="memo.php" method="POST">
        <input type="text" name="pass" id="pass">
        <input type="submit">
    </form>
</body>

</html>