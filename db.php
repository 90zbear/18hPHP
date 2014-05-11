<?php
// PDOによるデータベース接続
// $purposeで実行内容を変更
function connect_db($purpose = null, $args = null){
	try{
		$dbh = new PDO(DBSET, DBUSR, DBPSW);
			switch ($purpose) {
				case 'table_create':
					$sth = $dbh->prepare('CREATE TABLE IF NOT EXISTS posts(id int auto_increment PRIMARY KEY, title VARCHAR(255) NOT NULL, content VARCHAR(65535) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME)');
					$sth->execute();

					$sth2 = $dbh->prepare('CREATE TABLE IF NOT EXISTS comments(id int auto_increment PRIMARY KEY, pid int, title VARCHAR(255) NOT NULL, content VARCHAR(65535) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME)');
					$sth2->execute();
					print "create!";
					break;

				case 'post_write':
					$sth = $dbh->prepare('INSERT INTO posts (title, content, created_at, updated_at) VALUES (:title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
					$sth->bindValue(':title', $args['title'], PDO::PARAM_STR);
					$sth->bindValue(':content', $args['content'], PDO::PARAM_STR);
					$sth->execute();
					break;

				case 'edit_post':
					$sth = $dbh->prepare('UPDATE posts SET content=:content, updated_at=CURRENT_TIMESTAMP WHERE id=:pid');
					var_dump($args);
					$sth->bindValue(':content', $args['content'], PDO::PARAM_STR);
					$sth->bindValue(':pid', $args['pid'], PDO::PARAM_INT);
					$sth->execute();
					$loc = 'Location: /admin.php?id='.$args['pid'];
					header($loc);
					break;

				case 'comment_write':
					$sth = $dbh->prepare('INSERT INTO comments (pid, title, content, created_at, updated_at) VALUES (:pid, :title, :ccontent, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
					$sth->bindValue(':pid', $args['pid'], PDO::PARAM_INT);
					$sth->bindValue(':title', $args['title'], PDO::PARAM_STR);
					$sth->bindValue(':ccontent', $args['ccontent'], PDO::PARAM_STR);
					$sth->execute();
					$loc = 'Location: /index.php?id='.$args['pid'];
					header($loc);
					break;

				case 'post_all_view':
					$sth = $dbh->query('SELECT id, title, content, created_at, updated_at FROM posts ORDER BY updated_at DESC');
					$posts = array();
					while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
						$posts[] = $row;
					}
					return $posts;
					break;

				case 'comment_read':
					$sth = $dbh->prepare('SELECT id, title, content, created_at, updated_at FROM comments WHERE pid = :pid');
					$sth->bindValue(':pid', $args, PDO::PARAM_INT);
					$sth->execute();
					$posts = array();
					while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
						$posts[] = $row;
					}
					return $posts;
					break;

				case 'post_read':
					$sth = $dbh->prepare('SELECT id, title, content, created_at, updated_at FROM posts WHERE id = :id');
					$sth->bindValue(':id', $args, PDO::PARAM_INT);
					$sth->execute();
					$posts = array();
					while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
						$posts[] = $row;
					}
					return $posts;
					break;

				case 'delete_post':
					$sth = $dbh->prepare('DELETE FROM posts WHERE id = :id');
					$sth->bindValue(':id', $args, PDO::PARAM_INT);
					$sth->execute();
					$posts = array();
					while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
						$posts[] = $row;
					}
					return $posts;
					break;

				default:
					print "connect!";
					break;
			}
		$dbh = null;
	}catch(PDOException $e){
		print "error" . $e->getMessage() . "<br>";
		die();
	}
}

?>