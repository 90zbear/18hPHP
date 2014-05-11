<?php
session_start();

ini_set('display_errors',1);
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

$msg = "";

// var_dump($_POST);
if($_POST){
	if( $_POST['uid'] != NULL || $_POST['psw'] != NULL ){
		if($_POST['uid'] == DEFAULTUSR && $_POST['psw'] == PSW ){
			$_SESSION['login'] = true;
		}else{
			$msg = "認証エラー";
		}
	}
}

if($_GET){
	if(isset($_GET['mode'])){
		if($_GET['mode']=="logout"){
			$_SESSION = array();
			header('Location: /admin.php');
		}
	}
	if(isset($_GET['id'])){
		$posts = connect_db('post_read', $_GET['id']);
		if(!$posts){
			header('Location: /admin.php');
		}
		if(isset($_GET['mode'])){
			if($_GET['mode']=="delete"){
				connect_db('delete_post', $_GET['id']);
				header('Location: /admin.php');
			}
		}
	}
}else{
	$posts = connect_db('post_all_view');
}
?>

<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>No Search Tube</title>
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<link href="css/screen.css" rel="stylesheet" type="text/css" content="text/css; charset=utf-8" />
	<link href="css/style.css" rel="stylesheet" type="text/css" content="text/css; charset=utf-8" />
</head>
<body>
	<header id="admin">
		<div class="box"><h1><a href="/admin.php">No Search Tube</a></h1></div>
	</header>
	<div id="contents" class="box">
		<ul>

<?php
if(!isset($_SESSION['login'])){
	echo $msg;
	echo "</br>";
?><a href="/">一般モード</a><br><br>
<form action="admin.php" method="POST">
	id : <input type="text" value="" name="uid"><br>
	psw : <input type="password" value="" name="psw"><br>
	<input type="submit" value="login">
</form>

	<?php
}elseif($_SESSION['login'] == true){
	if($_GET){
		if(isset($_GET['mode'])){
			if($_GET['id'] && $_GET['mode'] == "edit"){
						?>
						<a href="/">一般モード</a> | <a href="/admin.php?mode=logout">ログアウト</a>
						<?php
						foreach($posts as $post){
						?>
						<li style="">
							投稿日時 : <?php echo htmlspecialchars($post['created_at']); ?>
							<h2><?php echo htmlspecialchars($post['title']); ?></h2>
							<div class="content">
								<form action="edit.php" method="POST">
									<textarea name="content" id="" cols="30" rows="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
									<input type="hidden" name="pid" value="<?php echo htmlspecialchars($_GET['id']); ?>"><br>
									<input type="submit" value="更新する">
								</form>

							</div>
						</li>
						<?php
						}
						?>
					<?php
					}
		}elseif($_GET['id']){
			?>
			<a href="/">一般モード</a> | <a href="/admin.php?mode=logout">ログアウト</a>
			<?php
			foreach($posts as $post){
			?>
			<li style="">
				投稿日時 : <?php echo htmlspecialchars($post['created_at']); ?> <a href="admin.php?id=<?php echo htmlspecialchars($post['id']); ?>&mode=delete"><button>削除する</button></a> <a href="admin.php?id=<?php echo htmlspecialchars($post['id']); ?>&mode=edit"><button>編集する</button></a>
				<h2><a href="admin.php?id=<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
				<div class="content">
					<?php echo nl2br(htmlspecialchars($post['content'])); ?>
				</div>
			</li>
			<?php
			}
			?>
		<?php
		}
	}else{
			?>
			<a href="/">一般モード</a> | <a href="/admin.php?mode=logout">ログアウト</a>
			<?php
			foreach($posts as $post){
			?>
			<li style="">
				投稿日時 : <?php echo htmlspecialchars($post['created_at']); ?>
				<h2><a href="admin.php?id=<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
			</li>
			<?php
			}
			?>

<?php
	}
}
?>
		</ul>

	</div>
</body>
</html>