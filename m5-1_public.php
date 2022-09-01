<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    </head>
    
    <body>
         <?php 
            
            //データベースに接続
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
            //テーブルを作成
            $sql = "CREATE TABLE IF NOT EXISTS tb5"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date DATETIME,"
            . "password TEXT"
            .");";
            
            //sql文の実行
            $stmt = $pdo->query($sql);
            
            //新規投稿の処理
            if( !empty($_POST["str"]) && !empty($_POST["comment"]) && empty($_POST["rewrite"]) && !empty($_POST["pass"]) ){
                
                //新しい行を追加するsql文
                $sql = $pdo -> prepare("INSERT INTO tb5 (name, comment, date, password) VALUES(:name, :comment, :date, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam('date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                
                //データを受信
                $name = $_POST["str"];
                $comment = $_POST["comment"];
                $date = date ("Y/m/d/ H:i:s");
                $password = $_POST["pass"];
                
                //sql文の実行
                $sql -> execute();
            
            //削除機能の処理    
            }elseif( !empty($_POST["delete"]) && !empty($_POST["deletepass"]) ){
                
                //対象投稿を削除するsql文
                $sql = "delete from tb5 WHERE id=:id AND password=:deletepass";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':deletepass', $deletepass, PDO::PARAM_STR);
                
                //データを受信
                $id = $_POST["delete"]; 
                $deletepass = $_POST["deletepass"];
                
                //sql文の実行
                $stmt->execute();
                 
            
            //編集機能の処理(名前とコメントをフォームに表示) 
            }elseif(!empty($_POST["edit"]) && !empty($_POST["editpass"]) ){
                
                //編集対象の行を抽出するsql文
                $sql = "SELECT * FROM tb5 WHERE id=:edit AND password=:editpass";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':edit', $edit, PDO::PARAM_INT);
                $stmt->bindParam(':editpass', $editpass, PDO::PARAM_STR);
                
                //データを受信
                $edit = $_POST["edit"];
                $editpass = $_POST["editpass"];
                
                //sql文の実行
                $stmt->execute();
                
                //抽出した行の名前とコメントを変数に代入
                $result=$stmt->fetchAll();
                foreach ($result as $row){
             
                    $editname = $row["name"];
                    $editcomment = $row["comment"];
              
                }
                
            
            //編集後の名前とコメントが入力された際の処理    
            }elseif( !empty($_POST["rewrite"]) && !empty($_POST["str"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) ){
               
                //編集後の名前とコメントをテーブルに書き込むsql文
                $sql = 'UPDATE tb5 SET name=:rewritename,comment=:rewritecomment,date=:date, password=:rewritepass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':rewritename', $rewritename, PDO::PARAM_STR);
                $stmt->bindParam(':rewritecomment', $rewritecomment, PDO::PARAM_STR);
                $stmt->bindParam(':rewritepass', $rewritepass, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
               
                //データを受信、日時を指定
                $id = $_POST["rewrite"]; //変更する投稿番号
                $rewritename =$_POST["str"];//編集後の名前
                $rewritecomment = $_POST["comment"]; //編集後のコメント
                $date = date("Y/m/d H:i:s");
                $rewritepass = $_POST["pass"];
               
                //sql文の実行
                $stmt->execute();
                
                
            }
            
        ?>
        
         <!--フォームを作って送信　名前とコメント-->
        <form action="" method="post">
            <!--編集のときは名前とコメントが表示されるように、PHPのコードを埋め込む-->
            <input type="text" name="str" placeholder="名前" value= "<?php if(!empty($editname)) {echo $editname;} ?>" ><br>
            <input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($editcomment)) {echo $editcomment;} ?>" >
            
            <!--編集対象の投稿番号をフォームに表示させる(フォームは隠れる)-->
            <input type="hidden" name="rewrite" value="<?php if(!empty($edit)) {echo $edit;} ?>"><br>
            <input type="password" name="pass" placeholder="パスワード">
            <input type="submit" name="submit"><br>
        </form>
        
        <!--削除対象番号　送信-->
        <form action="" method="post">
            <input type="number" name="delete" placeholder="削除対象番号"><br>
            <input type="password" name="deletepass" placeholder="パスワード">
            <input type="submit" value="削除"><br>
        </form>
        
        <!--編集対象番号　送信-->
        <form action="" method="post">
            <input type="number" name="edit" placeholder="編集対象番号"><br>
            <input type="password" name="editpass" placeholder="パスワード">
            <input type="submit" value="編集"><br>
        </form>
        
        [投稿一覧]<br>
        
        
        
        
        
        
        <?php
            //テーブルの中身を表示
            $sql = 'SELECT * FROM tb5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();//すべて取ってくるという意味
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].'<br>';
                echo "<hr>";
            }
    
        ?>
        
        
    </body>
    

</html>














