<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Clon de Twitter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <header class="navbar navbar-expand-lg navbar-light bg-light p-3 shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="/">
                Clone de Twitter
            </a>

            <!-- Botón de menú en pantallas pequeñas -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenido del menú -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="d-flex mx-auto position-relative">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar usuarios..." aria-label="Buscar usuarios">
                    <div id="suggestions" class="list-group position-absolute top-100 w-100"></div>
                </div>

                <!-- Información de seguidores -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/followers">
                            <strong>Seguidores:</strong> <span id="followersCount"> {{ Auth::user()->followers()->count() }} </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/following">
                            <strong>Seguidos:</strong>  <span id="followingCount"> {{ Auth::user()->followings()->count() }} </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const searchInput = document.getElementById('searchInput');
            const suggestions = document.getElementById('suggestions');

            searchInput.addEventListener('input', handleSearchInput);

            function handleSearchInput() {
                const query = searchInput.value.trim();
                if (query.length > 1) {
                    fetchUsers(query);
                } else {
                    clearSuggestions();
                }
            }

            function fetchUsers(query) {
                $.ajax({
                    url: `{{ route('search.users') }}`,
                    type: 'GET',
                    dataType: 'json',
                    data: { query: query },
                    success: function(data) {
                        users = data.users;
                        console.log(users);
                        if (users.length === 0)
                            clearSuggestions();
                        else
                            displaySuggestions(users);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function displaySuggestions(users) {
                clearSuggestions();
                users.forEach(user => {
                    const item = createSuggestionItem(user);
                    suggestions.appendChild(item);
                });
            }

            function createSuggestionItem(user) {
                const item = document.createElement('a');
                item.classList.add('list-group-item', 'list-group-item-action');
                //item.href = `/profile/${user.id}`;
                item.innerHTML = `
                    ${user.name} 
                    <button class="btn btn-sm ${user.is_following ? 'btn-danger' : 'btn-primary'} float-end">
                        ${user.is_following ? 'Dejar de seguir' : 'Seguir'}
                    </button>
                `;
                const followButton = item.querySelector('button');
                followButton.addEventListener('click', (event) => toggleFollow(event, user.id));
                return item;
            }

            function clearSuggestions() {
                suggestions.innerHTML = '';
            }

            function toggleFollow(event, userId) {
                event.preventDefault();
                url = "{{ route('toggle.follow', ':userId') }}";
                url = url.replace(":userId", userId);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { userId: userId, _token: "{{ csrf_token() }}" },
                    success: function (data) {
                        updateFollowButton(event.target, data.is_following);
                        updateHeaderCount(data.followers_count, data.following_count);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            function updateFollowButton(button, isFollowing) {
                button.textContent = isFollowing ? 'Dejar de seguir' : 'Seguir';
                button.classList.toggle('btn-danger', isFollowing);
                button.classList.toggle('btn-primary', !isFollowing);
            }

            function updateHeaderCount(followersCount, followingCount) {
                const followersSpan = document.getElementById('followersCount');
                const followingSpan = document.getElementById('followingCount');
                
                if (followersSpan) followersSpan.textContent = followersCount;
                if (followingSpan) followingSpan.textContent = followingCount;
            }

        });
    </script>
</body>
</html>
