<?php
//ini_set('display_errors', "on");
$id = $_GET['id'];
while ('1' == '1') {
    if (!file_exists('access')) {
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
unlink('access');
foreach ($datas as $data) {
    if ($data['id'] == $id) {
        $row['0'] = $data['id'];
        $row['1'] = $data['date'];
        $row['2'] = $data['type'];
        if (is_array($data['tag'])) {
            $row['3'] = $data['tag'];
        } else {
            $row['3'][] = $data['tag'];
        }
        $row['4'] = $data['originalname'];
        $row['5'] = $data['memo'];
        $item = 'true';
        $html = $row['5'];
        $html = ltrim($html, '＜');
        $html = rtrim($html, '＞');
        $html = preg_replace('/img([0-9]{14})/i', '<img src=webp/$1.webp style="width:640px;hieght:auto;max-width:50%;">', htmlspecialchars_decode($data['memo']));
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
    <script>
        function ShowLength(str) {
            document.getElementById("inputlength").innerHTML = str.length + "文字";
        }
    </script>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>データベース参照　<?= ($id) ?>の詳細</title>
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
                <a href="index.html">データ管理システム</a>
            </h1>
        </header>
        <div class="container">
            <div class="main">
                <div class="box">
                    <section>
                        <?php foreach ($rows as $row) : ?>
                            [ID<?= ($row[0]) ?>]<br>
                            [<?= ($row[4]) ?>]<br>
                            [日時<?php echo (substr($row[1], '0', '4') . "年" . substr($row[1], '4', '2') . "月" . substr($row[1], '6', '2') . "日" . substr($row[1], '8', '2') . "時" . substr($row[1], '10', '2') . "分" . substr($row[1], '12', '2') . "秒"); ?>]<br>
                            <?php
                            if (!isset($_COOKIE['size'])) {

                                if ($row['2'] == "img" || $row['2'] == "vrc") {
                                    echo ('<img src="png/' . $row['0'] . '.png" class="viewmedia">');
                                } elseif ($row['2'] == "snd") {
                                    echo ('<audio controls src="sound/' . $row['0'] . '.m4a"></audio>');
                                } elseif ($row['2'] == "mov") {
                                    echo ('<video controls src="rawmovie/' . $row['0'] . '.mp4" class="viewmedia"></video>');
                                }
                            } elseif ($_GET['origin'] == "1") {
                                if ($row['2'] == "img" || $row['2'] == "vrc") {
                                    echo ('<img src="png/' . $row['0'] . '.png" class="viewmedia">');
                                } elseif ($row['2'] == "snd") {
                                    echo ('<audio controls src="sound/' . $row['0'] . '.m4a"></audio>');
                                } elseif ($row['2'] == "mov") {
                                    echo ('<video controls src="rawmovie/' . $row['0'] . '.mp4" class="viewmedia"></video>');
                                }
                            }elseif ($_GET['origin'] == "2"){
                                if ($row['2'] == "img" || $row['2'] == "vrc") {
                                    echo ('<img src="l_webp/' . $row['0'] . '.webp" class="viewmedia">');
                                } elseif ($row['2'] == "snd") {
                                    echo ('<audio controls src="sound/' . $row['0'] . '.m4a"></audio>');
                                } elseif ($row['2'] == "mov") {
                                    echo ('<video controls src="rawmovie/' . $row['0'] . '.mp4" class="viewmedia"></video>');
                                }
                            } else {
                                if (($_COOKIE['size'] == "1")) {
                                    if ($row['2'] == "img" || $row['2'] == "vrc") {
                                        echo ('<img src="webp/' . $row['0'] . '.webp" class="viewmedia">');
                                    } elseif ($row['2'] == "snd") {
                                        echo ('<audio controls src="ssound/' . $row['0'] . '.ogg"></audio>');
                                    } elseif ($row['2'] == "mov") {
                                        echo ('<video controls src="rawmovie/' . $row['0'] . '.mp4" class="viewmedia"></video>');
                                    }
                                } else {
                                    if ($row['2'] == "img" || $row['2'] == "vrc") {
                                        echo ('<img src="png/' . $row['0'] . '.png" class="viewmedia">');
                                    } elseif ($row['2'] == "snd") {
                                        echo ('<audio controls src="sound/' . $row['0'] . '.m4a"></audio>');
                                    } elseif ($row['2'] == "mov") {
                                        echo ('<video controls src="rawmovie/' . $row['0'] . '.mp4" class="viewmedia"></video>');
                                    }
                                }
                            }
                            ?>
                            <form action="csv.php" method="post">
                                <input type="text" name="tag" style="min-width:20%;" id="tag" value="" onkeyup="ShowLength(value);"></input><br>
                                <script type="text/javascript">
                                    document.getElementById('tag').focus();
                                </script><br>
                                <?php
                                foreach ($row['3'] as $tags) {
                                    echo ('&lt;<a href="csv.php?tag=' . $tags . '&id=' . $row['0'] . '&mode=tagrm">[X]</a><a href="content.php?search=' . $tags . '">' . $tags . '</a>&gt;&nbsp;<br>');
                                }
                                ?><br>
                                <a href="data.php?id=<?= ($row['0']) ?>&origin=1">オリジナル画像の閲覧</a><br>
                                <a href="data.php?id=<?= ($row['0']) ?>&origin=2">高画質画像の閲覧</a><br>
                                <a href="png/<?= ($row['0']) ?>.png" download="<?= ($row['0']) ?>.png">画像をダウンロードする</a><br>
                                <a href="rawmovie/<?= ($row['0']) ?>.mp4" download="<?= ($row['0']) ?>.mp4">動画をダウンロードする</a><br>
                                <a href="file/<?= ($row['0']) ?>.zip" download="<?= ($row['0']) ?>.zip">アーカイブをダウンロードする</a><br>
                                [文字数<p id="inputlength" style="display:inline-flex">-文字</p>]
                                <textarea name="memo" rows="20" style="width: 100%;" onkeyup="ShowLength(value);"><?= ($row['5']) ?></textarea><br>
                                <button type="submit" name="id" value="<?= ($row['0']) ?>">登録</button>
                            </form>
                        <?php endforeach; ?>
                    </section>
                </div>
            </div>
            <div class="side">
                <?= htmlspecialchars_decode($html) ?>
            </div>
        </div>
        <!--フッター-->
        <footer>
            データ管理システム
        </footer>
    </div>
</body>

</html>