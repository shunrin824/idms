<?php
ini_set('display_errors', "on");
$id = $_GET['id'];
while('1' == '1'){
    if(!file_exists('access')){
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
unlink('access');
foreach($datas as $data){
    if($data['id'] == $id){
        $row['0'] = $data['id'];
        $row['1'] = $data['date'];
        $row['2'] = $data['type'];
        $row['3'] = $data['tag'];
        $row['4'] = $data['originalname'];
        $row['5'] = $data['memo'];
        $item = 'true';
        $html = $row['5'];
        $html = ltrim($html, '＜');
        $html = rtrim($html, '＞');
        $row['5'] = str_replace('＜', '', $row['5']);
        $row['5'] = str_replace('＞', '', $row['5']);
        $row['0'] = str_replace('id', '', $row['0']);
        $row['5'] = str_replace("<br>", '&#010;', $row['5']);
        $row['3'] = str_replace('＜', '', $row['3']);
        $row['3'] = str_replace('＞', '', $row['3']);
        $row['0'] = str_replace('id', '', $row['0']);
        $row['3'] = str_replace("<br>", '&#010;', $row['3']);
        $rows[] = $row;
        break;
    }
}
?>
<!DOCTYPE html>
<html>
 <head>
  <style>
.box {
    padding: 0.5em 1em;
    margin: 2em 0;
    border: dashed 2px #5b8bd0;/*点線*/
}
.box p {
    margin: 0; 
    padding: 0;
}
  </style>
  <script>
function ShowLength( str ) {
 document.getElementById("inputlength").innerHTML = str.length + "文字";
}
  </script>
  <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <title>データベース参照　<?=($id)?>の詳細</title>
  <link rel="stylesheet" type="text/css" href="/resource/lightbox.css" media="screen,tv" />
  <script type="text/javascript" charset="UTF-8" src="/resource/lightbox_plus.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
 </head>
 <body>
  <div class="wrapper">
<!--ここからヘッダー-->
   <header>
    <h1>
     <a href="/index.html">旬燐のwebサイト</a>
    </h1>
   </header>
   <div class="container">
    <div class="main">
     <section>
       <?php foreach ($rows as $row): ?>
        [ID<?=($row[0])?>]<br>
        [<?=($row[4])?>]<br>
        [日時<?php echo(substr($row[1], '0', '4')."年".substr($row[1], '4', '2')."月".substr($row[1], '6', '2')."日".substr($row[1], '8', '2')."時".substr($row[1], '10', '2')."分".substr($row[1], '12', '2')."秒");?>]<br>
        <?php
            if($_COOKIE['size'] == "1"){
                if($row['2'] == "img" || $row['2'] == "vrc"){
                    echo ('<img src="webp/'.$row['0'].'.webp" class="viewmedia">');
                }elseif($row['2'] == "snd"){
                    echo('<audio controls src="ssound/'.$row['0'].'.ogg"></audio>');
                }elseif($row['2'] == "mov"){
                    echo('<video controls src="smovie/'.$row['0'].'.webm" class="viewmedia"></video>');
                }
            }else{
                if($row['2'] == "img" || $row['2'] == "vrc"){
                    echo ('<img src="png/'.$row['0'].'.png" class="viewmedia">');
                }elseif($row['2'] == "snd"){
                    echo('<audio controls src="sound/'.$row['0'].'.m4a"></audio>');
                }elseif($row['2'] == "mov"){
                    echo('<video controls src="movie/'.$row['0'].'.webm" class="viewmedia"></video>');
                }
            }
        ?>
        <form action="csv.php" method="post">
         <input type="text" name="tag" style="min-width:20%;" id="tag" value="" onkeyup="ShowLength(value);"></input><br>
         <script type="text/javascript">
             document.getElementById('tag').focus();
         </script><br>
         <?php
foreach($row[3] as $tags){
echo('&lt;<a href="csv.php?tag='.$tags.'&id='.$row[0].'&mode=tagrm">[X]</a><a href="content.php?search='.$tags.'">'.$tags.'</a>&gt;&nbsp;<br>');
}
         ?><br>
        <a href="png/<?=($row[0])?>.png" download="<?=($row[0])?>.png">ダウンロードする</a><br>
        [文字数<p id="inputlength" style="display:inline-flex">-文字</p>]
         <textarea name="memo" rows="20" style="width: 100%;" onkeyup="ShowLength(value);"><?=($row[5])?></textarea><br>
         <button type="submit" name="id" value="<?=($row[0])?>">登録</button>
        </form>
        <form action="del.php" method="get" style="display:inline-flex">
         <button type="submit" name="id" value="<?=($row[0])?>">削除</button>
        </form>
        <form action="output.php" method="get" style="display:inline-flex">
         <button type="submit" name="id" value="<?=($row[0])?>">ダウンロード</button>
        </form>
        <form action="preview.php" method="get" style="display:inline-flex">
         <button type="submit" name="id" value="<?=($row[0])?>">プレビュー</button>
        </form>
       <?php endforeach; ?>
       <p>
        データベースには<?=htmlspecialchars($name0)?>のデータが見つかりませんでした。<br>
        データが登録されていないか、サーバーにエラーが起きています。<br>
       </p>
     </section>
    </div>
    <div class="side">
     <?=htmlspecialchars_decode($html)?>
    </div>
   </div>
<!--フッター-->
   <footer>
   当サーバーを利用して起こった損失などの責任は負いかねます。
   </footer>
  </div>
 </body>
</html>
