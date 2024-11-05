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
        <div class="container mx-auto py-12 px-6 bg-slate-700 rounded-lg mt-12">
            <div class="flex justify-between mb-4">
                <div>
                    <a href="{{ route('switch.language', 'en') }}" class="text-blue-400 hover:text-blue-600">English</a>
                    <a href="{{ route('switch.language', 'id') }}" class="text-blue-400 hover:text-blue-600 ml-2">Bahasa Indonesia</a>
                </div>
                <a href="{{ route('movies.favorites') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-star mr-2"></i> Favorite
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">{{ __('messages.logout') }}</button>
                </form>
            </div>

    <!-- pencarian -->
    <form action="{{ route('movies.index') }}" method="GET" class="flex gap-4">
    <input type="text" name="year" placeholder="Year" value="{{ request('year') }}" class="p-2 rounded bg-gray-200 text-black">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
</form>



            <!-- end pencarian -->

            <h2 class="text-2xl font-semibold mb-4 mt-12 text-start">{{ __('messages.listmovie') }}</h2>

            <div id="movie-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($movies as $movie)
                <div class="bg-gray-900 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-center py-3">{{ $movie['Title'] }}</h3>
                    <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}" class="w-full h-auto rounded mt-2">
                    <p class="py-3 text-start"><strong>Tahun : </strong> {{ $movie['Year'] }}</p>
                    <div class="flex justify-between mt-10">
                        <a href="{{ route('movies.detail', $movie['imdbID']) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">{{ __('messages.viewdetail') }}</a>
                        <form action="{{ route('movies.favorite', $movie['imdbID']) }}" method="POST" style="display:inline;">

                            @csrf
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">{{ __('messages.addtofavorite') }}</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="loader" class="hidden text-center py-4">
                <span class="loader">Loading..</span>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let page = 1; // Halaman awal
            let isLoading = false; // Status loading

            $(window).on('scroll', function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100 && !isLoading) {
                    page++;
                    loadMoreMovies(page);
                }
            });

            function loadMoreMovies(page) {

                isLoading = true;
                $('#loader').removeClass('hidden');

                $.ajax({
                    url: '{{ route('movies.index') }}',

                    method: 'GET',
                    data: {
                        page: page,
                        title: '{{ request("title") }}', // Ambil parameter pencarian
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#loader').addClass('hidden');
                        isLoading = false;

                        if (data && data.Search) {
                            data.Search.forEach(movie => {
                                $('#movie-list').append(`
                                    <div class="bg-gray-900 p-4 rounded-lg">
                                        <h3 class="text-lg font-bold text-center py-3">${movie.Title}</h3>
                                        <img src="${movie.Poster}" alt="${movie.Title}" class="w-full h-auto rounded mt-2">
                                        <p class="py-3 text-start"><strong>Tahun : </strong> ${movie.Year}</p>
                                        <div class="flex justify-between mt-10">
                        <a href="/movies/${movie.imdbID}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">{{ __('messages.viewdetail') }}</a>
                        <form action="/favorite/${movie.imdbID}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">{{ __('messages.addtofavorite') }}</button>
                        </form>
                    </div>
                                    </div>
                                `);
                            });

                            if (data.Search.length === 0) {
                                $(window).off('scroll');
                                $('#loader').text('No more movies to load.');
                            }
                        } else {
                            console.log("Tidak ada data film yang diterima.");
                        }
                    },
                    error: function() {
                        $('#loader').addClass('hidden');
                        isLoading = false;
                    }
                });
    } 
        </script>
    </body>

    </html>