<?php
require_once("env.php");

try {
    $pdo = new PDO(_DSN, _DB_USER, _DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $Exception) {
    die('エラー :' . $Exception->getMessage());
}

try {
    $sql = "SELECT * FROM main WHERE pass = :pass ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':pass',  $_POST["pass"],  PDO::PARAM_STR);
    $stmh->execute();
    $data = $stmh->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $Exception) {
    print "エラー：" . $Exception->getMessage();
}

if (!empty($data['pass'])) {
    $text = $data['text'];
    $pass = $data['pass'];
} else {
    try {
        $pdo->beginTransaction();
        $sql = "INSERT  INTO main (pass,text)
        VALUES ( :pass, :text )";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':pass',   $_POST["pass"],   PDO::PARAM_STR);
        $stmh->bindValue(':text',   '',   PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
        $text = '';
        $pass = $_POST['pass'];
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
    <title>webmemo</title>
</head>

<body>
    <form action="index.php" method="POST">
        <input type="hidden" name='pass' value="<?php echo $pass ?>">
        <textarea name='text'><?php echo $text; ?></textarea>
        <input type="submit">
    </form>

</body>

</html>