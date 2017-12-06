<?php
# 書き込みファイル
$dateFile = 'ranking.dat';


session_start();

function setToken(){
    $token = sha1(uniqid(mt_rand(),true));
    $_SESSION['token'] = $token;
}

function checkToken(){
    if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo "不正なPOSTが行われました!";
        exit;
    }

}

function h($s){
    return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['message']) && isset($_POST['user']) && isset($_POST['score']) ){
    checkToken();

    $message = trim($_POST['message']);
    $user = trim($_POST['user']);
    $score = trim($_POST['score']);


    if($message !== ''){

        $user = ($user === '') ?'名無しの遠藤' :$user;

        $message =str_replace("\t", ' ',$message);
        $user =str_replace("\t", ' ',$user);
        $score =str_replace("\t", ' ',$score);
        $postesAt=date('Y-m-d H:i:s');


        $newDate = $message ."\t".$user."\t".$score."\t".$postesAt."\n";


        $fp =fopen($dateFile,'a');
        fwrite($fp,$newDate);
        fclose($fp);
        }
    } else{
    setToken();
}

$posts=file($dateFile,FILE_IGNORE_NEW_LINES);
$posts = array_reverse($posts);

?>

    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Slot App</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <h2 class="title">Endo Slot</h1>
            <h3 id="message"></h3>
            <div class="slot">
                <div class="panel">
                    <img src="img/seven.png" width="90" height="110" alt="">
                    <div class="stop inactive" data-index="0">STOP</div>
                </div>
                <div class="panel">
                    <img src="img/seven.png" width="90" height="110" alt="">
                    <div class="stop inactive" data-index="1">STOP</div>
                </div>
                <div class="panel">
                    <img src="img/seven.png" width="90" height="110" alt="">
                    <div class="stop inactive" data-index="2">STOP</div>
                </div>
            </div>
            <div id="spin">SPIN?</div>
            <audio id="audio" preload="auto">
                <source src="audio/slot.mp3" type="audio/mp3">
            </audio>
            <div class="score_message">
                <p>スコア:
                    <span id="score"></span>
                </p>
            </div>
            <script src="js/main.js"></script>
            <!-- commets -->
            <div class="comments">
                <h3>自己申告でスコアを報告して下さい</h3>
                <form action="" method="post">
                    <ul>
                        <li>あなたの名前 :
                            <input type="text" name="user">
                        </li>
                        <li>スコア :
                            <input type="text" name="score">pt</li>
                        <li>一言どうぞ:
                            <input type="text" name="message">
                        </li>
                    </ul>
                    <input type="submit" value="投稿">
                    <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                    <p>※名前に何も入力が無ければ「名無しの遠藤」で投稿されます。</p>

                </form>
                <h2>Endo Slotランキング (
                    <?php echo count($posts); ?>件)</h2>
                <ul>
                    <?php if (count($posts)) : ?>
                    <?php foreach ($posts as $post) :?>
                    <?php list($message,$user,$score,$postesAt)=explode("\t",$post); ?>
                    <li>
                        <?php echo h($user); ?>( <?php echo h($score); ?> pt):<?php echo h($message); ?>-<?php echo h($postesAt); ?>
                    </li>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <li>投稿はまだありません</li>
                    <?php endif; ?>
                </ul>
            </div>
            <p>
                <a href="https://github.com/Fendo181/endoslot">ソースコード</a>
            </p>
    </body>
    </html>
