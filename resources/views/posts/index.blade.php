<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #333; }
        .alert { padding: 12px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .btn { display: inline-block; padding: 10px 20px; margin-bottom: 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background-color: #0056b3; }
        .post { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .post h2 { margin: 0 0 10px 0; color: #333; }
        .post p { margin: 10px 0; color: #666; }
        .post small { color: #999; }
    </style>
</head>
<body>
    <h1>Posts</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="/posts/create" class="btn">Create New Post</a>

        @forelse($posts as $post)
    <div class="post">
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>
        <small>By {{ $post->user->name }}</small>
    </div>
    @empty
        <p>No posts yet. <a href="/posts/create">Create one now!</a></p>
    @endforelse
</body>
</html>
