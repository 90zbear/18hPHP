<?php
ini_set('display_errors',1);
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

if($_POST){
	if( $_POST['title'] != NULL || $_POST['content'] != NULL ){
		connect_db('post_write', $_POST);
	}
}

if($_GET){
	if($_GET['id']){
		$posts = connect_db('post_read', $_GET['id']);
		$comments = connect_db('comment_read', $_GET['id']);
		if(!$posts){
			header('Location: /');
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
	<header>
		<div class="box"><h1><a href="/">No Search Tube</a></h1></div>
	</header>
	<div id="contents" class="box">

		<ul>
	<?php
	if($_GET){
		if($_GET['id']){
			?>
			<?php
			foreach($posts as $post){
			?>
			<li class="cf">
				投稿日時 : <?php echo htmlspecialchars($post['created_at']); ?>
				<h2><a href="/?id=<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
				<div class="youtube">
					<?php youtube(htmlspecialchars($post['title']), 'movie'); ?>
				</div>
				<div class="content">
					<?php echo nl2br(htmlspecialchars($post['content'])); ?>
				</div>
				<div class="comments">
					<?php
					foreach($comments as $comment){
						// var_dump($comment);
					?>
					<div class="comment">
						<span style="color:#D9443F;font-weight:bold;"><?php echo htmlspecialchars($comment['title']); ?></span>
						<p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
					</div>
					<?php } ?>
					<form action="comment.php" method="POST">
						<input type="text" value="" name="title"><br>
						<textarea name="ccontent" cols="30" rows="10"></textarea><br>
						<input type="hidden" name="pid" value="<?php echo htmlspecialchars($post['id']); ?>">
						<input type="submit" value="送信">
					</form>
				</div>
			</li>
			<?php
			}
			?>
			<?php
		}
	}else{
			?>
			<?php
			foreach($posts as $post){
			?>
			<li class="cf">
				<div class="youtube image">
					<?php youtube(htmlspecialchars($post['title']), 'image'); ?>
				</div>
				投稿日時 : <?php echo htmlspecialchars($post['created_at']); ?>
				<h2><a href="/?id=<?php echo htmlspecialchars($post['id']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
			</li>
			<?php
			}
			?>
		<form action="/" method="POST">
			<input type="text" value="" name="title"><br>
			<textarea name="content" cols="30" rows="10"></textarea><br>
			<input type="submit" value="送信">
		</form>
			<?php
	}
?>
		</ul>


	</div>
<div style="text-align:right;"><a href="/admin.php">管理モード</a></div>
</body>
</html>