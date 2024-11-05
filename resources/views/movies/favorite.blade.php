<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Favorite Movies</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-800 text-white">
    <div class="container mx-auto py-6">
        <h2 class="text-2xl font-bold py-7">Your Favorite Movies</h2>
        
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        
        @if($favorites->isEmpty())
            <p class="text-gray-500">No favorite movies added yet.</p>
        @else
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $favorite)
                    <li class="bg-gray-900 rounded-lg p-4 shadow-lg">
                        @if($favorite->movie)
                            <h5 class="text-lg text-center py-2 font-semibold">{{ $favorite->movie->Title }}</h5>
                            <img src="{{ $favorite->movie->Poster }}" alt="{{ $favorite->movie->Title }}" loading="lazy" class="img-thumbnail w-full h-64 object-cover rounded mb-3"> <!-- Menambahkan loading="lazy" -->
                            <p class="text-gray-400 text-sm pb-2">Year: {{ $favorite->movie->Year }}</p>
                            <p class="text-gray-300 text-justify min-h-24">Plot: {{ $favorite->movie->Plot }}</p> <!-- Set minimum height -->
                            <div class="flex justify-between mt-10 mb-2">
                                <a href="{{ route('movies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded">Back to List</a>
                                <form action="{{ route('movies.favorite.remove', $favorite->movie_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Remove</button>
                                </form>
                            </div>
                        @else
                            <h5 class="text-lg font-semibold">Movie not found</h5>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</body>

</html>
