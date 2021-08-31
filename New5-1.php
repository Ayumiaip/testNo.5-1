<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
        <?php
            //データベース接続
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
     
            //エラー表示を消す
            error_reporting(E_ALL & ~E_NOTICE);
            //定義一覧
                //名前
                $name=$_POST["name"];
                //コメント
                $str=$_POST["comment"];
                //投稿パスワード
                $pass=$_POST["pass"];
                //日時取得
                $date=date("Y/m/d H:i:s");
                //削除番号
                $delnum=$_POST["delnum"];
                //削除用パスワード
                $delpass=$_POST["delpass"];
                //編集番号
                $ednum=$_POST["ednum"];
                //編集パスワード
                $edpass=$_POST["edpass"];
            //投稿機能
                if(isset($_POST["submit"])){
                    if(!empty($_POST["name"])&& !empty($str)&& !empty($pass)&&
                        empty($delnum)&& empty($delpass)&& empty($ednum)&& empty($edpass)){
                            //日時取得
                            $date=date("Y/m/d H:i:s");
                            //テーブル指定
                            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                            $sql -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
                            $sql -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
                            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                            $sql -> bindParam(':password', $_POST["pass"], PDO::PARAM_STR);
                            $sql -> execute();
                    }   
                } 
            //削除機能
                //削除ボタンを押したら
                if(isset($_POST["delete"])){
                    //削除対象番号とパスワードが入力されたら
                    if(!empty($delnum)&& !empty($delpass)&& empty($name) && empty($str)
                        && empty($pass)&& empty($ednum)&& empty($edpass)){
                            $id=$delnum;
                            //パスワード取得
                            $sql = 'SELECT password FROM mission5 WHERE id=:id';
                            $stmt = $pdo->prepare($sql);                  //差し替えるパラメータを含めて記述したSQLを準備
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT); //差し替えるパラメータの値を指定
                            $stmt->execute();                             //SQLを実行
                            $results = $stmt->fetchAll(); 
                            foreach ($results as $row){
                                $DBdelpass=$row['password'];
                            }
                            
                            //パスワードがあっていれば
                            if(@$DBdelpass == $delpass){
                                //入力したデータレコードを削除
                                $sql = 'delete from mission5 where id=:id';
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                                $delsuc="を削除しました。";
                            }
                    }
                }
            //編集機能
                //編集ボタンが押されたら
                if(isset($_POST["edit"])){
                    //編集対象番号とパスワードが入力されたら
                    if(!empty($ednum) && !empty($edpass) && empty($name) && empty($str)
                        && empty($delnum) && empty($delpass) && empty($pass)){
                            $id=$ednum;
                            //パスワード取得
                            $sql = 'SELECT password FROM mission5 WHERE id=:id';
                            $stmt = $pdo->prepare($sql);                  
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);//差し替えるパラメータの値を指定
                            $stmt->execute();
                            $results = $stmt->fetchAll(); 
                            foreach ($results as $row){
                                //$rowの中にはテーブルのカラム名が入る
                                $DBedpass=$row['password'];
                            }
                    }
                            
                            if($DBedpass == $edpass){
                                //データレコード編集
                                //入力したデータレコードを抽出、変数に代入
                                $sql = 'SELECT * FROM mission5 WHERE id=:id';
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt->execute();
                                $results = $stmt->fetchAll(); 
                                foreach ($results as $row){
                                    $ednum=$row['id'];
                                    $edname=$row['name'];
                                    $edcomment=$row['str'];
                                    $edpassword=$row['password'];
                                    $ednumsuc="編集番号を受付けました。";
                                }
                    }
                }
                
                //内容編集
                //編集投稿ボタンが押されたら
                if(isset($_POST["newsub"])){
                    //名前、コメント、パスワードが空じゃなかったら
                    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["ednum"]) && !empty($_POST["pass"])){
                        $ednum=$_POST["ednum"];
                        $id = $ednumber;
                        $name = $_POST["name"];
                        $comment = $_POST["comment"];
                        $password = $_POST["pass"];
                        $sql = 'UPDATE mission5.1 SET name=:name,comment=:comment, password=:password WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        $edsuc="を編集しました。"; 
                    } 
                }
                
                //表示
                $sql = 'SELECT * FROM mission5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
        <form action="" method="post">
        <新規・編集投稿フォーム><br>
            <input type="text" name="name" placeholder="名前" value="<?php echo $edname; ?>"><br>
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $edstr;?>"><br>
            <input type="text" name="pass" placeholder="パスワード" value="<?php echo $edpass1;?>"><br>
            <input type="hidden" name="ednumber" value="<?php echo @$ednum;?>"><br>
            <input type="submit" name="submit" value="新規投稿"><br>
            <input type="submit" name="newsub" value="編集投稿">
            <br>
        <削除フォーム><br>
            <input type="number" name="delnum" placeholder="削除番号"><br>
            <input type="text" name="delpass" placeholder="パスワード"><br>
            <input type="submit" name="delete"value="削除"><br>
            <br>
        <編集番号送信フォーム><br>
            <input type="number" name="ednum"　placeholder="編集番号"><br>
            <input type="text" name="edpass" placeholder="パスワード"><br>
            <input type="submit" name="edit" value="編集"><br>
            <br>
        <br>
    ＊同時に新規投稿・削除・編集をすることはできません。<br>
　      それぞれ分けて実行をお願いします。<br>
    ＊パスワードなしでも投稿はできますが、編集・削除はできません。<br>
    <br>
    テーマ：旅行で行きたいところ<br>
    </form>
</body>