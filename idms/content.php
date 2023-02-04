<?php
ini_set("memory_limit", "4096M");
//ini_set('display_errors', "on");
$s = microtime(true); //処理にかかった時間。
//$config = json_decode(mb_convert_encoding(file_get_contents('config.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
$config = json_decode(file_get_contents('config.json'), 'true');
$folder_png = "/var/www/html/idms/png";
function h($str)
{ //テキストデータの処理を関数にしたもの。
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if (isset($_COOKIE['foretaste'])) { //データの表示スタイルに関する設定。
    $foretaste = $_COOKIE['foretaste']; //リスト表示（画像大きめ）。
}
if (isset($_COOKIE['fullsc'])) {
    $fullsc = $_COOKIE['fullsc']; //アルバム表示。
}

if ($_COOKIE['num'] == "1") { //画像の1ページあたりの最大表示件数に関する設定。
    $cntnum = $config['NumberOfDisplays1']; //デフォルトで100。
} elseif ($_COOKIE['num'] == "2") {
    $cntnum = $config['NumberOfDisplays2']; //デフォルトで200。
} elseif ($_COOKIE['num'] == "3") {
    $cntnum = $config['NumberOfDisplays3']; //デフォルトで500。
} elseif ($_COOKIE['num'] == "4") {
    $cntnum = $config['NumberOfDisplays4']; //デフォルトで1000。
} else {
    $cntnum = $config['NumberOfDisplays0']; //デフォルトで50。
}

$disk_free['PngFolder'] = round(disk_free_space($config['PngFolder']) / "1073741824", 2); //PNGを格納しているストレージの残容量を取得
$disk_tortal['PngFolder'] = round(disk_total_space($config['PngFolder']) / "1073741824", 2); //PNGを格納しているストレージの総容量を取得
$disk_use['PngFolder'] = round(($disk_tortal['PngFolder'] - $disk_free['PngFolder']) / $disk_tortal['PngFolder'] * "100", 2); //PNGを格納しているストレージの利用率を取得。

$disk_free['WebpFolder'] = round(disk_free_space($config['WebpFolder']) / "1073741824", 2); //WEBPを格納しているストレージの残容量を取得
$disk_tortal['WebpFolder'] = round(disk_total_space($config['WebpFolder']) / "1073741824", 2); //WEBPを格納しているストレージの総容量を取得
$disk_use['WebpFolder'] = round(($disk_tortal['WebpFolder'] - $disk_free['WebpFolder']) / $disk_tortal['WebpFolder'] * "100", 2); //WEBPを格納しているストレージの使用率を取得。

$name = h($_GET['search']);
if (empty($_GET['page'])) { //GETメソッドでページ指定がない場合、自動的に1ページ目として指定する。
    $n = 0;
    $page = 1;
} else { //GETメソッドでページ指定がある場合、その値を使う。
    $page = $_GET['page'];
    $n = ($page - 1);
}

if ($_COOKIE['r'] == "1") { //非表示ファイルを表示するかどうか
    unset($config['HideWord']);
}
if (empty($_GET['search'])) {
    $query = '＞';
} else {
    $q1 = explode(" ", str_replace('　', " ", $_GET['search'])); //検索ワードを空白で分ける。
    foreach ($q1 as $que) {
        if (mb_substr($que, 0, 1) == "-") { //先頭にハイフンがある場合除外ワードとする。
            $config['HideWord'][] = mb_substr($que, 1); //除外ワード群への追加。
            $hideque[] = mb_substr($que, 1); //除外ワードへの追加。
        } else { //先頭にハイフンがない場合は検索ワードとして使う。
            $q[] = $que; //検索ワード群への追加。
        }
    }
}
while (file_exists('access')) { //データの排他制御のためにロックファイルの存在確認。
    sleep("0.1"); //ロックファイルの存在を確認したためクールタイム。
}
$rt1 = microtime(true); //メタデータの読み込みにかかった時間を計測するため、処理開始時間を取得。
file_put_contents('access', 'reading'); //ロックファイルを作成。
//$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true'); //JSONファイルを読み込み。
$datas = json_decode(file_get_contents('data.json'), 'true'); //JSONファイルを読み込み。
unlink('access'); //ロックファイルを削除
$rt2 = microtime(true); //メタデータの読み込みにかかった時間を計測するため、処理終了時間を取得。
$nod = '0'; //ディスプレイ表示件数に関する設定。
foreach ($datas as $data) { //読み込んだデータの全件検索
    unset($nm); //除外用変数の初期化
    if (isset($_COOKIE['type'])) { //データタイプの読み込み
        if ($_COOKIE['type'] !== $data['type']) { //データタイプが一致するか確認
            $nm = "1"; //除外用変数を追加
        }
    }
    if (isset($q)) { //検索ワードが存在するか確認
        if (is_array($q)) { //検索ワードが複数存在するか確認。
            foreach ($q as $qs) { //検索ワードごとに検索
                if (isset($data['tag'])) { //タグデータが存在するかを確認。
                    if (is_array($data['tag'])) { //タグデータが複数存在するか確認。
                        if (!stristr(implode('', $data['tag']), $qs) && !stristr($data['memo'], $qs) && !stristr($data['originalname'], $qs)) { //検索ワードがタグデータとテキスト本文データに存在しない場合に除外用変数を追加。
                            $nm = "1";
                        }
                    } else {
                        if (!stristr($data['tag'], $qs) && !stristr($data['memo'], $qs) && !stristr($data['originalname'], $qs)) { //検索ワードがタグデータとテキスト本文データに存在しない場合に除外用変数を追加。
                            $nm = "1";
                        }
                    }
                } else {
                    $nm = "1";
                }
                unset($qs);
            }
        }
    }
    foreach ($config['HideWord'] as $hideword) { //非表示キーワード群の全件検索
        if (isset($data['tag'])) { //タグデータが存在することを確認
            if (is_array($data['tag'])) { //タグデータが複数存在することを確認。
                if (stristr(implode('', $data['tag']), $hideword) || stristr($data['memo'], $hideword) || stristr($data['originalname'], $hideword)) { //検索ワードがタグデータとテキスト本文データに存在しない場合に除外用変数を追加。
                    $nm = "1";
                }
            }
        }
        unset($hideword);
    }
    if (!isset($nm)) { //条件に合致した場合、カウント
        $nod++;
    }
    if ($nod < $cntnum * $page) { //表示データを件数でふるいにかける。ページ
        if ($nod >= $cntnum * $n) {
            if (empty($nm)) {
                if (isset($row['memo'])) { //memo変数にデータが存在する場合、150文字までにトリミングする。
                    $row['memo'] = mb_substr($row['memo'], 0, 150);
                }
                $row['0'] = $data['id']; //以下3行は、データを表示するための変数のコピー
                $row['1'] = $data['date'];
                $row['2'] = $data['type'];
                if (isset($data['tag'])) { //タグが存在するか、また連想配列で存在するかを確かめ、連想配列なら配列を結合させる。
                    if (is_array($data['tag'])) {
                        $row['3'] = '{' . implode('}{', $data['tag']) . '}';
                    }
                }
                if (isset($data['originalname'])) { //ファイルの名前が存在する場合、表示用変数に代入。
                    $row['4'] = $data['originalname'];
                }
                if (isset($data['memo'])) { //本文データが存在する場合、見出しとして一部を表示。
                    $row['5'] = mb_substr($data['memo'], 0, 100);
                }
                $rows[] = $row; //表示用変数に代入したデータを連想配列に保存。
            }
        }
    } else {
        goto end;
    }
    unset($data);
    unset($row);
}
end:
$count = "0";
if (isset($q)) { //表示タグ名の表示処理
    if (is_array($q)) {
        $name = '[' . implode(']、[', $q) . ']';
    } else {
        $name = "全て";
    }
} else {
    $name = "全て";
}
if (isset($hideque)) { //非表示タグ名の表示処理
    if (is_array($hideque)) {
        $name1 = "[-" . implode(']、[-', $hideque) . "]";
    } else {
        $name1 = '除外タグはありません。';
    }
}
$s2 = microtime(true);
?>
<!DOCTYPE html>
<html>

<head>
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
                <a href="index.html">データ管理システム</a>
            </h1>
        </header>
        <!--ここから本文-->
        <div class="container">
            <div class="main">
                <div class="box">
                    <a href="index.html">データベース（登録用ページ）</a>
                    <form action="content.php" method="get">
                        検索<input type="text" name="search" value="<?= ($_GET['search']) ?>"><input type="hidden" name="page" value="1"><button type="submit">検索</button>
                    </form>
                    PNG <?= ($disk_use['PngFolder']) ?>% FreeSpace<?= ($disk_free['PngFolder']) ?>GB<br>
                    WEBP <?= ($disk_use['WebpFolder']) ?>% FreeSpace<?= ($disk_free['WebpFolder']) ?>GB<br>
                    load<?= (substr(($rt2 - $rt1), 0, 5)) ?>s / process<?= (substr(($s2 - $s), 0, 5)) ?>s<br>
                    <?= ($nod) ?>件目<?= h($name) ?><?php if (!empty($hideque)) : ?><?= h($name1) ?><?php endif; ?><br>
                    <a href="cookie.php?foretaste=1&search=<?= h($_GET['search']) ?>&page=<?= h($_GET['page']) ?>">流し見</a>
                    <a href="cookie.php?fullsc=1&search=<?= h($_GET['search']) ?>&page=<?= h($_GET['page']) ?>">アルバム表示</a>
                    <a href="cookie.php?search=<?= h($_GET['search']) ?>&page=<?= h($_GET['page']) ?>">通常表示</a><br>
                    <a href="content.php?page=<?= ($page - 1) ?>&search=<?= ($_GET['search']) ?>">前ページ</a><?= ($page) ?><a href="content.php?page=<?= ($page + 1) ?>&search=<?= ($_GET['search']) ?>">次のページ</a>
                    <section>
                        <?php if (!empty($rows)) : ?>
                            <?php if (!empty($foretaste)) : ?>
                                <div class="wrap">
                                    <table>
                                        <?php foreach ($rows as $row) : ?>
                                            <tr>
                                                <?php ++$count; ?>
                                                <th>
                                                    <?php if (strpos($row['3'], 'favorite')) : ?>
                                                        <a href="csv.php?tag=favorite&id=<?= $row['0'] ?>&mode=tagrm" target="csv">除外</a>
                                                    <?php else : ?>
                                                        <a href="csv.php?tag=favorite&id=<?= $row['0'] ?>&mode=tagadd" target="csv">追加</a>
                                                    <?php endif; ?>
                                                    <a href="png/<?= ($row['0']) ?>.png" download="<?= ($row['0']) ?>.png">DL_origin</a><br>
                                                    <a href="l_webp/<?= ($row['0']) ?>.webp" download="<?= ($row['0']) ?>.webp">DL_high</a><br>

                                                </th>
                                                <th>
                                                    <a href="data.php?id=<?= h(sprintf('%014d', $row['0'])) ?>&local=<?= ($_GET['local']) ?>">
                                                        <?php
                                                        $file_name = sprintf('%014d', $row['0']);
                                                        if (file_exists('webp/' . $file_name . '.webp')) {
                                                            if ($_COOKIE['size'] == "1") {
                                                                echo ('<img src="webp/' . $file_name . '.webp" style="padding: 0px;margin: 0px;max-height: 480px;">');
                                                            } else {
                                                                echo ('<img src="png/' . $file_name . '.png" style="padding: 0px;margin: 0px;max-height: 480px;">');
                                                            }
                                                        } else if ($row['2'] == "txt") {
                                                            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">' . $row['5'] . '</textarea>');
                                                        } else if ($row['2'] == "url") {
                                                            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">' . $row['5'] . '</textarea>');
                                                        } else {
                                                            echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">画像がありません</textarea>');
                                                        }
                                                        ?>
                                                    </a>
                                                </th>
                                                <th>
                                                    <textarea style="width:100%;height:22%;padding:0px;margin:0px;"><?= ($row['3']) ?></textarea><br>
                                                    <textarea style="width:100%;height:22%;padding:0px;margin:0px;"><?= ($row['4']) ?></textarea>
                                                </th>
                                            </tr>
                                            <?php unset($row) ?>
                                        <?php endforeach; ?>
                                    </table>
                                    <div><iframe src="" width="1px" height="1px" name="csv">処理用です</iframe></div>
                                </div>
                            <?php else : ?>
                                <div class="wrap">
                                    <?php foreach ($rows as $row) : ?>
                                        <?php ++$count; ?>
                                        <div class="<?php
                                                    if (!empty($fullsc)) {
                                                        echo ("fsc");
                                                    } else {
                                                        echo ("cnt");
                                                    }
                                                    ?>">
                                            <a href="data.php?id=<?= h(sprintf('%014d', $row['0'])) ?>" target="_blank">
                                                <?php
                                                $file_name = sprintf('%014d', $row['0']);
                                                if (file_exists('webp/' . $file_name . '.webp')) {
                                                    if ($_COOKIE['size'] == "1" && !empty($fullsc)) {
                                                        echo ('<img src="webp/' . $file_name . '.webp" style="max-height: 338px;max-width: 600px">');
                                                    } elseif (empty($fullsc)) {
                                                        echo ('<img src="webp/' . $file_name . '.webp"style="padding:0px;margin:0px;width:100%;height:56%;">');
                                                    } elseif (!empty($fullsc)) {
                                                        echo ('<img src="png/' . $file_name . '.png" style="max-height: 338px;max-width: 600px">');
                                                    }
                                                } else if ($row['2'] == "txt" && empty($fullsc)) {
                                                    echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">' . $row['5'] . '</textarea>');
                                                } else if ($row['2'] == "url" && empty($fullsc)) {
                                                    echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">' . $row['5'] . '</textarea>');
                                                } else if (empty($fullsc)) {
                                                    echo ('<textarea style="padding:0px;margin:0px;width:100%;height:46%;">画像がありません</textarea>');
                                                }
                                                ?>
                                            </a>
                                            <?php
                                            if (empty($fullsc)) {
                                                echo ('<textarea style="width:100%;height:21%;padding:0px;margin:0px;">' . $row['3'] . '</textarea><br>');
                                                echo ('<textarea style="width:100%;height:21%;padding:0px;margin:0px;">' . $row['4'] . '</textarea>');
                                            }
                                            ?>
                                        </div>
                                        <?php unset($row) ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <p>
                                データベースには<?= ($name0) ?>のデータが見つかりませんでした。<br>
                                データが登録されていないか、サーバーにエラーが起きています。<br>
                            </p>
                        <?php endif; ?>
                        <a href="content.php?page=<?= ($page - 1) ?>&search=<?= ($_GET['search']) ?>">前ページ</a><?= ($page) ?><a href="content.php?page=<?= ($page + 1) ?>&search=<?= ($_GET['search']) ?>">次のページ</a>
                </div>

                <div class="box">
                    <form action="cookie.php" method="get">
                        検索<input type="text" name="search" value="<?= ($_GET['search']) ?>"><br>
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
                        <input type="radio" name="num" value="4">10000<br>
                        <input type="radio" name="size" value="0">高画質
                        <input type="radio" name="size" value="1">低画質<br>
                        <button type="submit">検索</button><br>
                    </form>
                    </section>
                </div>
            </div>
        </div>
        <!--フッター-->
        <footer>
            データ管理システム
        </footer>
    </div>
</body>

</html>