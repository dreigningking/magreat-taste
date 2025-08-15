<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Blog Posts Available</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .post-title {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .post-excerpt {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .cta-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Blog Posts Available!</h1>
        <p>We've published {{ $posts->count() }} new articles you might enjoy.</p>
    </div>

    <div class="content">
        @foreach($posts as $post)
        <div class="post-item" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <h2 class="post-title">{{ $post->title }}</h2>
            
            @if($post->excerpt)
                <p class="post-excerpt">{{ $post->excerpt }}</p>
            @endif

            <a href="{{ url('/blog/' . $post->slug) }}" class="cta-button">Read Full Article</a>
        </div>
        @endforeach

        <p>Thank you for being a part of our community!</p>
    </div>

    <div class="footer">
        <p>You're receiving this email because you're subscribed to our newsletter.</p>
        <p>If you no longer wish to receive these emails, please <a href="{{ url('/unsubscribe') }}">unsubscribe here</a>.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
