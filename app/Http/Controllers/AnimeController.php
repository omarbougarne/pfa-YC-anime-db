<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Comment;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnimeController extends Controller
{

    public function index(Request $request)
{
    $search = $request->query('search');

    if ($search) {
        $animes = Anime::where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower($search) . '%')
            ->with('studio', 'statu')
            ->paginate(1); // Use paginate for pagination
    } else {
        $animes = Anime::with('studio', 'statu')->paginate(1); // Use paginate for pagination
    }

    return view('anime.index')->with('animes', $animes);
}
// Import the User model if not already imported

public function userList(Request $request)
{

    $animes = Anime::get();
    return view('anime.user_list')->with('animes', $animes);
}

public function addToUserList(Request $request, $id)
{
    $anime = Anime::find($id);
    if (!$anime) {
        return redirect(route('animes.index'))->with('error', 'Anime not found');
    }

    $user = Auth::user();
    $user->animes()->attach($anime, ['user_id' => $user->id]);

    return redirect(route('animes.user_list'))->with('success', $anime->title . ' added to your list');
}
public function showUserList($userId)
{
    // Retrieve the user's list of anime
    $user = User::findOrFail($userId); // Assuming you have a User model
    $animes = $user->animes()->with('studio', 'statu')->get();

    // Pass the user and the list of anime to the view
    return view('anime.user_list', ['animes' => $animes, 'user' => $user]);
}




    public function searchInUserList(Request $request)
    {
        $search = $request->query('search');


        $animes = $request->animes()->where('title', 'LIKE', '%' . $search . '%')->withPivot('watched', 'rating');


        return view('anime.user_list')->with('animes', $animes);
    }

public function removeFromList(Request $request, $id)
{
    $anime = Anime::find($id);

    if (!$anime) {
        return redirect(route('animes.index'))->with('error', 'Anime not found');
    }

    // Remove the anime from the database
    $anime->delete();

    return redirect(route('animes.index'))->with('success', $anime->title . ' has been removed');
}

public function create()
{
    $studios = StudioController::getStudios();
    $status = StatusController::getStatuss();
    $mangas = Manga::all(); // Fetch all mangas

    return view('anime.create')->with('studios', $studios)->with('status', $status)->with('mangas', $mangas);
}


    public function store(Request $request)
{
    $request->validate($this->getRules());

    $anime = new Anime();
    $anime->title = $request->input('title');
    $anime->synopsis = $request->input('synopsis');
    $anime->score = $request->input('score');

    if ($request->image) {
        $anime->image = $request->image->store('images', 'public');
    }

    // Get the manga name based on the manga_id
    $mangaName = Manga::findOrFail($request->input('manga_id'))->name;
    $anime->manga_name = $mangaName;
    $anime->studio_id = $request->input('studio');
    $anime->statu_id = $request->input('statu');
    $anime->save();


    $anime->save();

    return redirect(route('animes.index'));
}


    public function updateRating(Request $request, $id)
    {

        $anime = Anime::find($id);


        $request->animes()->updateExistingPivot($anime, ['rating' => $request->rating]);

        return redirect(route('animes.user_list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $anime = Anime::with('episodes')->find($id); // Load episodes relationship
    $comment = new Comment();

    if ($anime) {
        return view('anime.show')->with(['anime' => $anime, 'comment' => $comment]);
    } else {
        return abort(404);
    }
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anime = Anime::find($id);

        $studios = StudioController::getStudios();
        $status = StatusController::getStatuss();

        return view('anime.edit')->with('anime', $anime)->with('studios', $studios)->with('status', $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate($this->getRules());

        $anime = Anime::find($id);

        $anime->title = $request->input('title');
        $anime->synopsis = $request->input('synopsis');
        $anime->score = $request->input('score');

        if ($request->image) {
            if ($anime->image && Storage::exists($anime->image)) {
                Storage::delete($anime->image);
            }

            $anime->image = $request->image->store('images' , 'public');
        }

        // $anime->episodes = $request->input('episodes');
        // $anime->source = $request->input('source');
        $anime->studio_id = $request->input('studio');
        $anime->statu_id = $request->input('statu');

        $anime->save();

        return redirect(route('animes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anime = Anime::find($id);

        Storage::delete($anime->image);
        $anime->delete();

        return redirect(route('animes.index'));
    }

    public function editComment($id)
    {
        $comment = Comment::find($id);



        $anime = $comment->anime;

        return view('anime.show', compact('anime', 'comment'));
    }

    public function updateComment(Request $request, $id)
    {
        $comment = Comment::find($id);



        $request->validate([
            'body' => 'required',
        ]);

        $comment->body = $request->body;
        $comment->save();

        return redirect()->route('animes.show', $comment->anime_id);
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);



        $comment->delete();

        return back();
    }

    public function updateWatched(Request $request, $id)
    {
        // Validating the input
        // $request->validate([
        //     'watched' => 'required|integer|min:0|max:' . Anime::find($id)->episodes,
        // ]);

        // Find the entry in the pivot table for this anime and user
        $userAnime = $request->animes()->where('anime_id', $id)->first();

        // Update "watched" in the pivot table
        $userAnime->pivot->update(['watched' => $request->watched]);

        return redirect(route('animes.user_list'));
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'body' => 'required',
        ]);

        $anime = Anime::findOrFail($id);

        $comment = new Comment;
        $comment->body = $request->body;
        // $comment->user_id = $request->user()->id;

        $anime->comments()->save($comment);

        return back();
    }

    public function getRules()
    {
        $rules = [
            'title' => 'required|max:100',
            'synopsis' => 'required|max:3000',
            'score' => 'required|numeric|between:0,10',
            'image' => 'required|mimes:jpg,jpeg,png',
            // 'episodes' => 'required|numeric',
            'source' => 'required|max:30'
        ];
        return $rules;
    }

    public function getRulesMessages()
    {
        $msg = [
            'title.*' => '',
            'synopsis.*' => '',
            'image.*' => '',
            'score.*' => '',
            // 'episodes.*' => '',
            // 'source.*' => ''
        ];
        return $msg;
    }
}
