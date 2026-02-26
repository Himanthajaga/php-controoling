<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { resize: vertical; min-height: 150px; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: #dc3545; font-size: 0.875rem; margin-top: 5px; }
        .alert { padding: 12px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Create a New Post</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h4>Validation Failed!</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/posts" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Title:</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                placeholder="Enter post title"
                required
            >
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Content:</label>
            <textarea
                id="content"
                name="content"
                placeholder="Enter post content (minimum 10 characters)"
                required
            >{{ old('content') }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="user_id">Author:</label>
            <select id="user_id" name="user_id" required>
                <option value="">-- Select an author --</option>
                @php
                    $users = \App\Models\User::all();
                @endphp
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Create Post</button>
        <a href="/posts" style="margin-left: 10px; text-decoration: none;">Cancel</a>
    </form>
</body>
</html>

