<?php

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$db_username = 'root';
$db_password = 'root';
$erroe_messages = array();

try {
    $pdo = new PDO('mysql:host=mysql;dbname=bbs', $db_username, $db_password);
} catch (Exception $e) {
    echo $e->getMessage();
}

// フォームをSubmitした時に動く処理
if (!empty($_POST['submitButton'])) {

    if (empty($_POST['username'])) {
        $erroe_messages['username'] = '名前を入力してください';
        echo $erroe_messages['username'];
    }

    if (empty($_POST['comment'])) {
        $erroe_messages['comment'] = 'コメントを入力してください';
        echo $erroe_messages['comment'];
    }

    if (empty($erroe_messages)) {
        $post_date = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO `bbs_table` (`name`, `comment`, `date`) VALUES (:name, :comment, :date)");

        $stmt->bindParam(':name', $_POST['username']);
        $stmt->bindParam(':comment', $_POST['comment']);
        $stmt->bindParam(':date', $post_date);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}



$sql = 'SELECT `id`, `name`, `comment`, `date` FROM bbs_table';
$comment_array = $pdo->query($sql);
$pdo = null;
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2チャンネル掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <!-- メッセージ送信成功時 -->
        <?php if (!empty($success_message)) : ?>
            <p class="success_message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- バリデーションチェック時 -->
        <?php if (!empty($error_message)) : ?>
            <?php foreach ($error_message as $value) : ?>
                <div class="error_message">※<?php echo $value; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <section>
            <?php foreach ($comment_array as $comment) : ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前：</span>
                            <p class="username"><?php echo $comment['name'] ?></p>
                            <time>:<?php echo $comment['date'] ?></time>
                        </div>
                        <p class="comment"><?php echo $comment['comment'] ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
        <form method="POST" action="" class="formWrapper">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="usernameLabel">名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea name="comment" class="commentTextArea"></textarea>
            </div>
        </form>
    </div>

</body>

</html>