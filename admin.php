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
    <title>Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-form,
        .admin-panel {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        textarea {
            height: 300px;
            resize: vertical;
        }

        button {
            background: #3498db;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .posts-list {
            margin-top: 2rem;
        }

        .post-item {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Handle login
        if ($_POST['action'] === 'login') {
            if ($_POST['username'] === ADMIN_USERNAME && password_verify($_POST['password'], ADMIN_PASSWORD)) {
                $_SESSION['admin_logged_in'] = true;
                $message = "Login successful!";
            } else {
                $error = "Invalid credentials!";
            }
        }

        // Handle logout
        if ($_GET['action'] === 'logout') {
            session_destroy();
            header('Location: admin.php');
            exit;
        }

        // Check if admin is logged in
        if (!$_SESSION['admin_logged_in']):
            ?>

            <div class="login-form">
                <h2>Admin Login</h2>
                <?php if ($error): ?>
                    <div class="error"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>

        <?php else:

            // Handle post creation/editing
            if ($_POST['action'] === 'save_post') {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $post_id = $_POST['post_id'] ?? null;

                if ($post_id) {
                    // Update existing post
                    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
                    $stmt->execute([$title, $content, $post_id]);
                    $message = "Post updated successfully!";
                } else {
                    // Create new post
                    $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
                    $stmt->execute([$title, $content]);
                    $message = "Post created successfully!";
                }
            }

            // Handle post deletion
            if ($_POST['action'] === 'delete_post') {
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$_POST['post_id']]);
                $message = "Post deleted successfully!";
            }

            // Get post for editing
            $editing_post = null;
            if ($_GET['edit']) {
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$_GET['edit']]);
                $editing_post = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            ?>

            <div class="admin-panel">
                <div class="header">
                    <h2>Admin Panel</h2>
                    <div>
                        <a href="index.php" style="margin-right: 1rem;">View Site</a>
                        <a href="?action=logout">Logout</a>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="success"><?= $message ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="action" value="save_post">
                    <?php if ($editing_post): ?>
                        <input type="hidden" name="post_id" value="<?= $editing_post['id'] ?>">
                        <h3>Edit Post</h3>
                    <?php else: ?>
                        <h3>Create New Post</h3>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($editing_post['title'] ?? '') ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <!-- <label>Content:</label>
                        <textarea name="content" required><?= htmlspecialchars($editing_post['content'] ?? '') ?></textarea> -->
                        <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

                        <div class="form-group">
                            <label>Content:</label>
                            <textarea name="content" required>
                                                    <?= htmlspecialchars($editing_post['content'] ?? '') ?>
                                                </textarea>
                        </div>

                        <script>
                            tinymce.init({
                                selector: 'textarea[name=content]',
                                plugins: 'paste',
                                menubar: false,
                                toolbar: 'undo redo | bold italic underline | bullist numlist link',
                                paste_as_text: false
                            });
                        </script>
                    </div>

                    <button type="submit"><?= $editing_post ? 'Update Post' : 'Create Post' ?></button>
                    <?php if ($editing_post): ?>
                        <a href="admin.php" style="margin-left: 1rem;">Cancel</a>
                    <?php endif; ?>
                </form>

                <div class="posts-list">
                    <h3>Manage Posts</h3>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
                    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($posts as $post):
                        ?>
                        <div class="post-item">
                            <div>
                                <strong><?= htmlspecialchars($post['title']) ?></strong>
                                <div style="font-size: 0.9rem; color: #666;">
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                </div>
                            </div>
                            <div class="post-actions">
                                <a href="?edit=<?= $post['id'] ?>">
                                    <button class="btn-small">Edit</button>
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this post?')">
                                    <input type="hidden" name="action" value="delete_post">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="btn-danger btn-small">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</body>

</html>