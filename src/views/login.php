<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'no name'?></title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>
    <header class="header">
    <img class="img" src="../images/logo.png" alt="bank logo">
    </header>
    <main class="main">
        <h1></h1>
        <h4 style="margin-left: 200px; color: red; margin-bottom: 0;"><?php
    require __DIR__ . '/msg.php';
    ?></h4>
        
        <h2 style="margin-left: 200px; " >Please LOGIN:</h2>
        
        <form style="background-color: #7F99A1; padding: 10px" action="" method="post" class="new" name="singin">
            Username: <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
            Password: <input type="password" name="password" required />
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <button type="submit" value="login" name="login" class="btn">Login</button>
        </form>
        <?php
    require __DIR__ . '/bottom.php';
    ?>