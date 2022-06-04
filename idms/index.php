<!DOCTYPE html>
<html>
 <head>
  <style>
.box {
    border: dashed 2px #5b8bd0;/*点線*/
}
  </style>
  <meta charset="UTF-8">
  <title>
   データベース（登録用ページ）
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/style.css">
  <style>
.image-upload .preview {
    display: block;
    width: auto;
    max-width: 300px;
    height: auto;
    max-height: 300px;
}
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
        // アップロードボタン
        var fileSelector = '.image-upload';
         
        // プレビュー画像のクラス名
        var previewSelector = '.preview';
 
        // プレビューするファイルタイプ
        var fileTypes = [
            'image/jpeg', 'image/jpg', 'image/png',
            'image/gif', 'image/bmp'
        ];
 
        if( typeof FileReader == 'undefined' ){
            return;
        }
 
        var reader = new FileReader();
 
        reader.addEventListener('load', function(event) {
            preview.setAttribute('src', event.target.result);
        });
 
        var fileInputs = document.querySelectorAll(fileSelector);
 
        for(var i = 0; i < fileInputs.length; i++){
            var fileInput = fileInputs[i];
            var input = fileInput.querySelector('input');
            var preview = fileInput.querySelector(previewSelector);
 
            if(!preview) return;
            preview.setAttribute('data-fallback-src', preview.getAttribute('src'));
 
            input.addEventListener('change', function(){
                if(input.files && input.files[0]　&& fileTypes.indexOf(input.files[0].type) >= 0) {
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.setAttribute('src', preview.getAttribute('data-fallback-src'));
                }
            });
        }
    });
  </script>
  <script>
function ShowLength( str ) {
 document.getElementById("inputlength").innerHTML = str.length + "文字";
}
  </script>
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
     <h2><a href="/db/">データベースホームに戻る</a></h2>
     <?php echo $filename; ?>
     <div class="box">
      <p>
      <h1>データベース登録</h1>
       <section>
        <p>
         <div class="image-upload">
          <form action="save.php" method="post" enctype="multipart/form-data">
           <input type="file" name="file"><br>
           <button type="submit">登録</button><br>
           <input type="radio" name="type" value="txt">テキスト</input>
           <input type="radio" name="type" value="mov">動画</input>
           <input type="radio" name="type" value="fil">ファイル</input>
           <input type="radio" name="type" value="tod">予定</input>
           <img class="preview" src="noimage.jpg" alt="Preview">
           タグ<br>
           <textarea name="tag" rows="10" style="max-width:100%;width:500px"></textarea><br>
           URL<br>
           <input type="text" name="url" value=""></input><br>
           メモ<br>
           [文字数<p id="inputlength" style="display:inline-flex">-文字</p>]<br>
           <textarea name="memo" rows="10" style="max-width:100%;width:500px" onkeyup="ShowLength(value);"></textarea><br>
          </form>
         </div>
        </p>
       </section>
      </p>
     </div>
     <div class="box">
      <form action="cookie.php" method="get">
       検索<input type="text" name="search"><br>
       ※全角スペースで区切ってください。<br>
<input type="radio" name="type" value="all" checked="checked">すべて
<input type="radio" name="type" value="vrc">VRC写真
<input type="radio" name="type" value="txt">メモ
<input type="radio" name="type" value="fil">ファイル
<input type="radio" name="type" value="img">画像
<input type="radio" name="type" value="mov">動画
<input type="radio" name="type" value="tod">予定<br>
<input type="radio" name="r" value="0" checked="checked">制限
<input type="radio" name="r" value="1">解除<br>
<input type="radio" name="num" value="0" checked="checked">50
<input type="radio" name="num" value="1">100
<input type="radio" name="num" value="2">200
<input type="radio" name="num" value="3">500
<input type="radio" name="num" value="4">1000<br>
<input type="radio" name="size" value="0">高画質
<input type="radio" name="size" value="1">低画質<br>
       <button type="submit">検索</button><br>
       <a href="content.php">イラスト一覧</a><br>
       <a href="foretaste_r.php">イラスト一覧()</a><br>
       <img id="preview">
      </form>
     </div>
    </div> 
<!--ここからサイドメニュー-->
    <div class="side">
     <!--<iframe width="100%" height="500px" src="side.html">このページはiframe対応ブラウザで表示できます。</iframe><br>-->
    </div>
   </div>
<!--フッター-->
   <footer>
    当サーバーを利用して起こった損失などの責任は負いかねます。
   </footer>
  </div>
 </body>
</html>
