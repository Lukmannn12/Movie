<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\FavoriteMovie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    private $client;
    private $apiKey = '1c3edb20'; // Ganti dengan kunci API yang valid

    public function __construct()
    {
        $this->client = new Client();
    }

    // Controller
    // app/Http/Controllers/MovieController.php

    public function index(Request $request)
    {
        // Ambil nomor halaman dari request, default ke 1
        $page = $request->input('page', 1);
        $year = $request->input('year');


        // Buat panggilan API dengan parameter page
        $response = Http::get("http://www.omdbapi.com/", [
            'apikey' => '1c3edb20', // Ganti dengan API key yang valid
            's' => 'page',  // Ganti dengan parameter pencarian yang sesuai
            'y' => $year, // Tambahkan tahun ke parameter
            'page' => $page,
        ]);

        // Cek apakah respons berhasil
        if ($response->successful()) {
            $data = $response->json();
            $movies = $data['Search'] ?? [];

            $movies = $data['Search'] ?? [];
                foreach ($movies as &$movie) {
                    $movie['Plot'] = $movie['Plot'] ?? 'Plot not available';
                }


            $totalResults = (int) $data['totalResults'] ?? 0;

            // Jika request AJAX, kembalikan data JSON
            if ($request->ajax()) {
                return response()->json([
                    'Search' => $movies,
                    'totalResults' => $totalResults,
                ]);
            }

            // Kembalikan tampilan dengan data film
            return view('movies.index', compact('movies', 'totalResults', 'page'));
        }

        return abort(500, 'Failed to fetch data');
    }
    

    


    // Fungsi untuk detail film
    public function detailMovie($id)
    {
        try {
            $response = $this->client->get("http://www.omdbapi.com/?apikey={$this->apiKey}&i={$id}");
            $movie = json_decode($response->getBody()->getContents(), true);

            // Cek jika film ditemukan
            if (isset($movie['Title'])) {
                return view('movies.detail', compact('movie'));
            } else {
                return redirect()->route('movies.index')->withErrors(['error' => 'Movie not found.']);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return back()->withErrors(['error' => 'Failed to fetch movie details.']);
        }
    }

    public function addFavorite($id)
    {
        // Ambil detail film dari API
        $response = $this->client->get("http://www.omdbapi.com/?apikey={$this->apiKey}&i={$id}");
        $movie = json_decode($response->getBody()->getContents(), true);

        // Simpan film ke tabel movies jika belum ada
        Movie::updateOrCreate(
            ['imdbID' => $movie['imdbID']], // Atau bisa juga menambahkan kondisi lain
            [
                'Title' => $movie['Title'],
                'Year' => $movie['Year'],
                'Poster' => $movie['Poster'],
                'Plot' => $movie['Plot'] ?? 'No plot information available',
                'imdbRating' => $movie['imdbRating'],
            ]
        );

        // Tambahkan ke favorit
        $favorite = new FavoriteMovie();
        $favorite->user_id = Auth::id();
        $favorite->movie_id = $movie['imdbID']; // Menggunakan imdbID dari API
        $favorite->save();

        return redirect()->route('movies.favorites')->with('success', 'Movie added to favorites!');
    }




    public function favoriteMovies()
    {
        $favorites = FavoriteMovie::where('user_id', Auth::id())
            ->with('movie') // Mengambil relasi movie
            ->get();
        return view('movies.favorite', compact('favorites'));
    }



    public function removeFavorite($id)
    {
        $favorite = FavoriteMovie::where('user_id', Auth::id())->where('movie_id', $id)->first();
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Movie removed from favorites!');
        }
        return back()->withErrors(['error' => 'Favorite movie not found.']);
    }

    public function switchLanguage($lang)
    {
        if (in_array($lang, ['en', 'id'])) {
            session(['applocale' => $lang]);
        }
        return redirect()->back();
    }
}
