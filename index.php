<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JCX67578T3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-JCX67578T3');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Archive</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .admin-link:hover {
            color: white;
        }

        .newsletter-post {
            background: white;
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .newsletter-post h2 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .post-meta {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .post-content {
            white-space: pre-line;
        }

        .archive-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .archive-list {
            list-style: none;
        }

        .archive-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .archive-list li:last-child {
            border-bottom: none;
        }

        .archive-list a {
            text-decoration: none;
            color: #2c3e50;
        }

        .archive-list a:hover {
            color: #3498db;
        }

        .no-posts {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            margin: 2rem 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <header style="position: relative;">
            <h1>Stocks and States</h1>
            <p>Weekly financial insights and updates from Ferris Montana</p>
            <a href="admin.php" class="admin-link">Admin</a>
        </header>

        <?php
        include 'config.php';

        // Get the latest post
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 1");
        $latest_post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($latest_post):
            ?>
            <div class="newsletter-post">
                <h2><?= htmlspecialchars($latest_post['title']) ?></h2>
                <div class="post-meta">
                    Published on <?= date('F j, Y', strtotime($latest_post['created_at'])) ?>
                </div>
                <div class="post-content"><?= $latest_post['content'] ?></div>
            </div>
        <?php endif; ?>

        <div class="archive-section">
            <h3>Archive</h3>
            <?php
            // Get all posts except the latest one
            $stmt = $pdo->query("SELECT id, title, created_at FROM posts ORDER BY created_at DESC" . ($latest_post ? " LIMIT 1000 OFFSET 1" : ""));
            $archive_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($archive_posts):
                ?>
                <ul class="archive-list">
                    <?php foreach ($archive_posts as $post): ?>
                        <li>
                            <a href="post.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                                <span style="float: right; color: #7f8c8d; font-size: 0.8rem;">
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-posts">No archived posts yet.</div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>