<?php
$lines = file('bbs.txt');

if ($_POST['write'] && $_POST['mode'] != 'editmode') {

  //投稿番号をつくる処理
  $items = explode("\t", $lines);
  $no = count($lines) + 1 ;

  //データを変数に格納
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $pass = $_POST['pass'];
  $time = date("Y/m/d H:i:s");

  //データを文字列でつくる処理
  $data = "$no\t$name\t$comment\t$pass\t$time\n";
  array_push($lines, $data);
}

//削除の処理
if ($_POST['delete']) {
  for ($i = 0; $i < count($lines); $i++) {
    $items = explode("\t", $lines[$i]);
    if ($items[0] == $_POST['delno'] && $items[3] == $_POST['delkey']) {
      array_splice($lines, $i, 1);
    }
  }
}

//編集ボタンを押したときの処理
if ($_POST['edit']) {
  for ($i = 0; $i < count($lines); $i++) {
    $items = explode("\t", $lines[$i]);
    if ($items[0] == $_POST['edino'] && $items[3] == $_POST['edikey']) {
      $edit_name = $items[1];
      $edit_comment = $items[2];
      $edit_pass = $items[3];
    }
  }
}

//編集内容を変更するときの処理
if ($_POST['mode'] == 'editmode') {
  for ($i = 0; $i < count($lines); $i++) {
    $items = explode("\t", $lines[$i]);
    if ($items[0] == $_POST['edino']) {
      $items[1] = $_POST['name'];
      $items[2] = $_POST['comment'];
      $items[3] = $_POST['pass'];

      $lines[$i] = "$items[0]\t$items[1]\t$items[2]\t$items[3]\t$items[4]\n";

    }
  }
}


//書き込みも編集もしないときの処理
if ($_POST['write'] || $_POST['delete']) {
  $fp = fopen('bbs.txt', 'w');
  foreach($lines as $line) fputs($fp, $line);
  fclose($fp);
}

?>



<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>簡易掲示板</title>
</head>
<body>
<form method="post" action="">
お名前：<input type="text" name="name" value="<?php echo $edit_name; ?>"><br>
コメント：<input type="text" name="comment" value="<?php echo $edit_comment; ?>"><br>
<?php
if($_POST['edino']){
  $edino = $_POST['edino'];
echo '<input type="hidden" name="mode" value="editmode">';
echo '編集番号<input type="text" name="edino">';
}
?>

パスワード：<input type="password" name="pass" value="<?php echo $edit_pass; ?>"><br>
<input type="submit" name="write" value="送信">
</form>
<hr>

<form method="post" action="">
削除指定番号：<input type="text" name="delno">
　パスワード: <input type="password" name="delkey">
　<input type="submit" name="delete" value="削除">
</form>

<form method="post" action="">
編集指定番号：<input type="text" name="edino">
　パスワード: <input type="password" name="edikey">
　<input type="submit" name="edit" value="編集">
</form>
<hr>

<?php
foreach($lines as $line) {
  $line = trim($line);
  $items = explode("\t", $line);
  print "No.{$items[0]}　";
  print "投稿者：";
  print "$items[1]";
  print "　投稿時間：{$items[4]}";
  print "<p>{$items[2]}</p><hr>\n";
}
?>

</body>
</html>
