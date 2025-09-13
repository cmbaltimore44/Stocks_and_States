<?php include 'config.php'; ?>
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
    <title>Newsletter Post</title>
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

        .back-link {
            color: #3498db;
            text-decoration: none;
            margin-bottom: 2rem;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .newsletter-post {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .newsletter-post h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .post-meta {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ecf0f1;
        }

        .post-content {
            white-space: pre-line;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Archive</a>

        <?php
        $post_id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post):
            ?>
            <div class="newsletter-post">
                <h1><?= htmlspecialchars($post['title']) ?></h1>
                <div class="post-meta">
                    Published on <?= date('F j, Y', strtotime($post['created_at'])) ?>
                </div>
                <?php
                $allowed_tags = '<p><br><b><i><strong><em><u><ul><ol><li><h1><h2><h3><h4><h5><h6><a>';
                $safe_content = strip_tags($post['content'], $allowed_tags);
                ?>
                <div class="post-content"><?= $safe_content ?></div>
            <?php else: ?>
                <div class="newsletter-post">
                    <h1>Post Not Found</h1>
                    <p>The requested post could not be found.</p>
                </div>
            <?php endif; ?>
        </div>
</body>

</html>