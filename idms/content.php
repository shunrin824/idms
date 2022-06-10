<?php
//ini_set('display_errors', "on");
$s = microtime(true);//処理にかかった時間。
$config = json_decode(mb_convert_encoding(file_get_contents('config.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
function h($str) {//テキストデータの処理を関数にしたもの。
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if(isset($_COOKIE['foretaste'])){//データの表示スタイルに関する設定。
    $foretaste = $_COOKIE['foretaste'];//リスト表示（画像大きめ）。
}
if(isset($_COOKIE['fullsc'])){
    $fullsc = $_COOKIE['fullsc'];//アルバム表示。
}

if($_COOKIE['num'] == "1"){//画像の1ページあたりの最大表示件数に関する設定。
    $cntnum = $config['NumberOfDisplays1'];//デフォルトで100。
}elseif($_COOKIE['num'] == "2"){
    $cntnum = $config['NumberOfDisplays2'];//デフォルトで200。
}elseif($_COOKIE['num'] == "3"){
    $cntnum = $config['NumberOfDisplays3'];//デフォルトで500。
}elseif($_COOKIE['num'] == "4"){
    $cntnum = $config['NumberOfDisplays4'];//デフォルトで1000。
}else{
    $cntnum = $config['NumberOfDisplays0'];//デフォルトで50。
}


$name = h($_GET['search']);
if (empty($_GET['page'])){//GETメソッドでページ指定がない場合、自動的に1ページ目として指定する。
    $n = 0;
    $page = 1;
}else{//GETメソッドでページ指定がある場合、その値を使う。
    $page = $_GET['page'];
    $n = ($page - 1);
}

if(empty($_GET['search'])){
    $query = '＞';
}else{
    $q1 = explode(" ", str_replace('　', " ", $_GET['search']));//検索ワードを空白で分ける。
    foreach($q1 as $que){
        if(mb_substr($que, 0, 1) == "-"){//先頭にハイフンがある場合除外ワードとする。
            $config['HideWord'][] = mb_substr($que, 1);//除外ワード群への追加。
            $hideque[] = mb_substr($que, 1);//除外ワードへの追加。
        }else{//先頭にハイフンがない場合は検索ワードとして使う。
            $q[] = $que;//検索ワード群への追加。
        }
    }
}
while(file_exists('access')){//データの排他制御のためにロックファイルの存在確認。
    sleep("0.1");//ロックファイルの存在を確認したためクールタイム。
}
$rt1 = microtime(true);//メタデータの読み込みにかかった時間を計測するため、処理開始時間を取得。
file_put_contents('access', 'reading');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
unlink('access');
$rt2 = microtime(true);//メタデータの読み込みにかかった時間を計測するため、処理終了時間を取得。
$nod = '0';//ディスプレイ表示件数に関する設定。
foreach($datas as $data){
    unset($nm);
    if(isset($_COOKIE['type'])){
        if($_COOKIE['type'] !== $data['type']){
            $nm="1";
        }
    }
    if(isset($q)){
        if(is_array($q)){
            foreach($q as $qs){
                if(isset($data['tag'])){
                    if(is_array($data['tag'])){
                        if(stristr(implode('', $data['tag']), $qs) || stristr($data['memo'], $qs)){
                        }else{
                            $nm ="1";
                        }
                    }else{
                        if(stristr($data['tag'], $qs) || stristr($data['memo'], $qs)){
                        }else{
                            $nm ="1";
                        }
                    }
                }
            }
        }
    }
    if($_COOKIE['r'] !== "1"){
        foreach($config['HideWord'] as $hideword){
            if(isset($data['tag'])){
                if(is_array($data['tag'])){
                    if(stristr(implode('', $data['tag']), $hideword) || stristr($data['memo'], $hideword)){
                        $nm = "1";
                    }
                }
            }
        }
    }
    if(!isset($nm)){
        $nod++;
    }
    if($nod < $cntnum * $page){
        if($nod >= $cntnum * $n){
            if(empty($nm)){
                if(isset($row['memo'])){
                    $row['memo'] = mb_substr($row['memo'], 0, 150);
                }
                $row['0'] = $data['id'];
                $row['1'] = $data['date'];
                $row['2'] = $data['type'];
                if(isset($data['tag'])){
                    if(is_array($data['tag'])){
                        $row['3'] = '{'.implode('}{', $data['tag']).'}';
                    }
                }
                if(isset($data['originalname'])){
                    $row['4'] = $data['originalname'];
                }
                if(isset($data['memo'])){
                    $row['5'] = mb_substr($data['memo'], 0, 100);
                }
                $rows[] = $row;
            }
        }
    }else{
        goto end;
    }
}
end:
if( empty($cnt) ){
$cnt = "0";
}
$count = "0";
if(isset($q)){
    if(is_array($q)){
        $name = '['.implode(']、[', $q).']';
    }else{
        $name = "全て";
    }
}
if(isset($hideque)){
    if(is_array($hideque)){
        $name1 = "[".implode(']、[', $hideque)."]";
    }else{
        $name1 = '除外タグはありません。';
    }
}
$s2 = microtime(true);
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
  <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <title>データベース参照　<?php echo $name0; ?>の検索結果</title>
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
<!--ここから本文-->
   <div class="container">
    <div class="main">
     <a href="index.html">データベース（登録用ページ）</a>へ<br><?=($nod)?>件目まで表示<br>
     <a href="cookie.php?foretaste=1&search=<?=h($_GET['search'])?>&page=<?=h($_GET['page'])?>">流し見</a>
     <a href="cookie.php?fullsc=1&search=<?=h($_GET['search'])?>&page=<?=h($_GET['page'])?>">アルバム表示</a>
     <a href="cookie.php?search=<?=h($_GET['search'])?>&page=<?=h($_GET['page'])?>">通常表示</a>
     <form action="content.php" method="get">
      検索<input type="text" name="search"><input type="hidden" name="page" value="1"><button type="submit" >検索</button><br>検索時間<?=(substr(($s2 - $s), 0, 5))?>秒<br>json読み込み<?=(substr(($rt2 - $rt1), 0, 5))?>秒<br>
     </form>
     <section>
      <?=h($name)?>のタグがついている画像を表示中<br><?php if(!empty($hideque)):?><?=h($name1)?>のタグを除外中<br><?php endif;?>
      <?php if(!empty($rows)): ?>
       <?php if(!empty($foretaste)): ?>
        <div class="wrap">
         <table>
          <?php foreach ($rows as $row): ?>
           <tr>
            <?php ++$count; ?>
            <th>
             <a href="data.php?id=<?=h(sprintf('%014d', $row['0']))?>&local=<?=($_GET['local'])?>" target="_blank">
             <?php
             $file_name = sprintf('%014d', $row['0']);
             if(file_exists('webp/'.$file_name.'.webp')){
              if($_COOKIE['size'] == "1"){
               echo ('<img src="webp/'.$file_name.'.webp" style="padding: 0px;margin: 0px;max-height: 480px;">');
              }else{
               echo ('<img src="png/'.$file_name.'.png" style="padding: 0px;margin: 0px;max-height: 480px;">');
              }
             }else if($row['2'] == "txt"){
              echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">'.$row['5'].'</textarea>');
             }else if($row['2'] == "url"){
              echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">'.$row['5'].'</textarea>');
             }else{
              echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">画像がありません</textarea>');
             }
             ?>
             </a>
            </th>
            <th>
             <textarea style="width:100%;height:22%;padding:0px;margin:0px;"><?=($row['3'])?></textarea><br>
             <textarea style="width:100%;height:22%;padding:0px;margin:0px;"><?=($row['4'])?></textarea>
            </th>
           </tr>
          <?php endforeach; ?>
         </table>
        </div>
       <?php else: ?>
        <div class="wrap">
         <?php foreach ($rows as $row): ?>
          <?php ++$count; ?>
          <div class="<?php
          if(!empty($fullsc)){
              echo("fsc");
          }else{
              echo("cnt");
          }
          ?>">
           <a href="data.php?id=<?=h(sprintf('%014d', $row['0']))?>" target="_blank">
           <?php
           $file_name = sprintf('%014d', $row['0']);
           if(file_exists('webp/'.$file_name.'.webp')){
            if($_COOKIE['size'] == "1" && !empty($fullsc)){
             echo ('<img src="webp/'.$file_name.'.webp" style="max-height: 338px;max-width: 600px">');
            }elseif(empty($fullsc)){
             echo ('<img src="webp/'.$file_name.'.webp"style="padding:0px;margin:0px;width:100%;height:56%;">');
            }elseif(!empty($fullsc)){
             echo ('<img src="png/'.$file_name.'.png" style="max-height: 338px;max-width: 600px">');
            }
           }else if($row['2'] == "txt" || empty($fullsc)){
            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">'.$row['5'].'</textarea>');
           }else if($row['2'] == "url" || empty($fullsc)){
            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">'.$row['5'].'</textarea>');
           }else if(empty($fullsc)){
            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">画像がありません</textarea>');
           }
           ?>
           </a>
           <?php
           if(empty($fullsc)){
           echo('<textarea style="width:100%;height:21%;padding:0px;margin:0px;">'.$row['3'].'</textarea><br>');
           echo('<textarea style="width:100%;height:21%;padding:0px;margin:0px;">'.$row['4'].'</textarea>');
           }
           ?>
          </div>
         <?php endforeach; ?>
        </div>
       <?php endif; ?>
      <?php else: ?>
      <p>
      データベースには<?=($name0)?>のデータが見つかりませんでした。<br>
      データが登録されていないか、サーバーにエラーが起きています。<br>
      </p>
      <?php endif; ?>
      <a href="content.php?page=<?=($page - 1)?>&search=<?=($_GET['search'])?>">前ページ</a><?=($page)?><a href="content.php?page=<?=($page + 1)?>&search=<?=($_GET['search'])?>">次のページ</a>
     </section>
    </div>
   </div>
<!--フッター-->
   <footer>
   当サーバーを利用して起こった損失などの責任は負いかねます。
   </footer>
  </div>
 </body>
</html>
