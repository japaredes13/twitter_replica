<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Clon de Twitter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Mensaje de éxito para el inicio de sesión o posteo -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="mb-4">Inicio</h1>

        <!-- Formulario para crear un nuevo "tweet" -->
        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="¿Qué está pasando?" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Tweet</button>
        </form>

        <!-- Lista de publicaciones o tweets -->
        <div class="mt-5">
            <h2>Últimos Tweets</h2>
            @forelse($posts as $post)
                <div class="card my-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->user->name }}</h5>
                        <p class="card-text">{{ $post->content }}</p>
                        <p class="text-muted">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p>No hay tweets para mostrar.</p>
            @endforelse
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
