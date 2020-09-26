<?php
// 今月を0とする。
// ???????カレンダーのボタン操作の0ポイントにする
$month = 0;

// GETパラメータがあって、かつ、数値形式で、かつ、整数のとき。
// isset()は、変数が存在していて、そして NULLとは異なるときにtrueを返却します。
// is_numeric()関数は、引数が「数値文字列」の場合にtrueを返却します。
// ???????ここでの多くの確認は、慣習として?、実務では拡張を見越して?行う?
// is_int()関数は、引数が「数値文字列」の場合はfalseを返却しますので、integerにキャストしています。
// HTMLのFORMからPOSTまたはGETで送信された値やgetパラメータ（クエリストリング、クエリパラメータ）
// で受け取った「数値」は「文字列」になっています。
// ???????  is_int()内の(int)はintegerへのキャストのキーワード
// nullになってるから
if (isset($_GET['month']) && is_numeric($_GET['month']) && is_int((int) $_GET['month'])) {
    $month = (int) $_GET['month'];
}

// 今日の日付のDateTimeクラスのインスタンスを生成します。
// ??????????  仮に今日になっているが、目的は表示を、その月のカレンダーにするため
// https://www.php.net/manual/ja/datetime.construct.php
$dateTime = new DateTime();

// タイムゾーンを「アジア/東京」にします。DateTimeZoneクラスを使用します。
// XAMPPのPHPのタイムゾーンは、デフォルトで「ヨーロッパ/ベルリン」になっています。
// https://www.php.net/manual/ja/datetimezone.construct.php
$dateTime->setTimezone(new DateTimeZone('Asia/Tokyo'));

// 今日の日付から(今日の日付 - 1)を引き、DateTimeクラスのインスタンスを今月の1日の日付に設定します。
//?????????月のどの日であっても、日付の値を１にするには残す１以外の数を全部引くのが、(今日の日付 - 1)の式の意味
// 日付のフォーマットの引数の書式は、こちらを参照してください。（date()関数と同じものが使えます。）
// https://www.php.net/manual/ja/function.date.php
$d = $dateTime->format('d');

// 'P'  ピリオド期間を表す。'D' デイ一日を表わす    'M' マンス月を表す
// sub()メソッド
$dateTime->sub(new DateInterval('P' . ($d - 1) . 'D'));
//?????????  これ以下では$dateTimeは１日を指す
if ($month > 0) {
    // $monthが0より大きいときは、
    //現在月の「ついたち」(????????28行目で行ったもの)に、その月数を追加します。
    //?????????  $monthの値に代入に当月の何か月前、後の月かを計算

    $dateTime->add(new DateInterval("P" . $month . "M"));
} else {
    // $monthが0より小さいときは、現在月の「ついたち」から、その月数を引きます。
    $dateTime->sub(new DateInterval("P" . (0 - $month) . "M"));
}

// 当月の「ついたち」が何曜日か求めます。当月の「ついたち」までに何日あるか、という日数と等しくなります。
// ????????????日曜は0であり、日曜始まりのカレンダーならついたちまでの日数は0
$beginDayOfWeek = $dateTime->format('w');       

// 当月に何日あるかの日数を求めます。              ?????????  t 指定した月の日数
$monthDays = $dateTime->format('t');

// 当月に何週あるかを求めます。小数点以下を切り上げることで、同月の週数が求められます。
//?????????? ceil() 切り上げの数学関数     $beginDayOfWeek  一週目に残る、先月末の日数でもある  
$weeks = ceil(($monthDays + $beginDayOfWeek) / 7);   

// カレンダーに記述する日付のカウンタ。
//1~28.29.30.31まで増える
$date = 1;


?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>練習問題07-5</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        th {
            text-align: center;
        }
        td {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container">
            <div class="row my-3">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <?= $dateTime->format('Y年n月') ?>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>日</th>
                                    <th>月</th>
                                    <th>火</th>
                                    <th>水</th>
                                    <th>木</th>
                                    <th>金</th>
                                    <th>土</th>
                                </tr>
                                <!-- 当月にある週数分繰り返し -->
                                <?php for ($week = 0; $week < $weeks; $week++) : ?>
                                    <!-- phpが<tr></tr>の生成を繰り返す -->
                                    <tr>
                                        <!-- 一週間の日数分（7日分）繰り返し -->
                                        <?php for ($day = 0; $day < 7; $day++) : ?>
                                            <!-- phpが<td></td>の生成を繰り返す -->
                                            <td>
                                                <?php
                                                if ($week == 0 && $day >= $beginDayOfWeek) {
                                                    // $week if文内の定義で、
                                                    // 月の1週目で、かつ、月初の日（曜日）以上のときは、
                                                    // 日付のカウンタを表示して、1を足す
                                                    // 
                                                    echo $date++;
                                                    //  ???? ↑ ここは$date
                                                } elseif ($week > 0 && $date <= $monthDays) {
                                                    // 月の2週目以降で、かつ、月末の日までのときは、
                                                    // 日付のカウンタを表示して、1を足す
                                                    echo $date++;
                                                    // ???? ↑ ここは$date
                                                }
                                                // その他の日は何も表示しない
                                                // その他の日、、、先月、来月の日にちは表示されない
                                                ?>
                                            </td>
                                        <?php endfor ?>
                                    </tr>
                                <?php endfor ?>
                            </table>
                        </div>
                        <div class="card-footer" style="text-align: center;">
                            <a href="./?month=<?= $month - 1 ?>" class="btn btn-outline-primary">&lt;&lt;前の月</a>
                            <a href="./" class="btn btn-primary">今月</a>
                            <a href="./?month=<?= $month + 1 ?>" class="btn btn-outline-primary">次の月&gt;&gt;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>



    </div>
</body>

</html>