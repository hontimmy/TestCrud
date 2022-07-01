<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Models\News;
use App\Events\NewsCreated;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//
		$news = News::all();
		 return view('news.index')
		 ->with('news', $news);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		 return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		$data = $request->all();
		$data['user_id'] = $request->user()->id;
        
		// Form Validation
        $validator = Validator::make($data, [
			'user_id' => 'required',
            'title' => 'required',
            'content' => 'required'
        ]);
		
		 if($validator->fails()){
			 
            return response([$validator->messages()->getMessages(), 'Validation Error']);
        }

		// Create News
        $news = News::create($data);
		
		// Fire News Event
		broadcast(new NewsCreated($news))->toOthers();

        return redirect('/news/'.$news->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('news.show',compact('news'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
		return view('news.edit',compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $news->update($request->all());
		
        return redirect('/news/'.$news->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect("/news");
    }
	
	
	
	/**
     * Cronjob deleting news after 14 days
	 *
	 * return job
	 *
	**/
	public function CronJob()
	{
		// Delete record older 14 days
		News::whereDate('created_at', '<=', now()->subDays(14))->delete();		
	}
}
