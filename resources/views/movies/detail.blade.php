<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['Title'] }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-800 text-white">
    <div class="container mx-auto py-12 px-6 bg-slate-700 rounded-lg mt-12 mb-24">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-center items-center">{{ $movie['Title'] }}</h2>
            <a href="{{ route('movies.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Back to List</a>
        </div>

        <div class="flex flex-col md:flex-row gap-4 py-4">
            <!-- Poster Image -->
            <div class="w-full md:w-1/4">
                <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}" loading="lazy" class="rounded-lg shadow-lg w-full h-auto object-cover"> <!-- Menambahkan loading="lazy" -->
            </div>

            <!-- Movie Details -->
            <div class="w-full md:w-3/4 bg-gray-900 p-4 rounded-lg shadow-lg">
                <p class="text-lg mb-2"><strong>Year:</strong> {{ $movie['Year'] }}</p>
                <p class="text-lg mb-4"><strong>Plot:</strong> {{ $movie['Plot'] ?? 'No description available.' }}</p>
                <p class="text-lg mb-4"><strong>Actors:</strong> {{ $movie['Actors'] ?? 'N/A' }}</p>
                <p class="py-3 text-start"><strong>Runtime: </strong> {{ $movie['Runtime'] ?? 'Runtime not available' }}</p>
                <p class="py-3 text-start"><strong>Released: </strong> {{ $movie['Released'] ?? 'Released not available' }}</p>
                <div class="flex gap-4 mt-4">
                    <form action="{{ route('movies.favorite', $movie['imdbID']) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Add to Favorites</button>
                    </form>
                    <a href="{{ route('movies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
