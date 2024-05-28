<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactUs;
use App\Models\DMCA;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Posttype;
use App\Models\Quality;
use App\Models\RequestUs;
use App\Models\Visitor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->share('uniquevisitorcount', Visitor::all()->count());

        view()->share('countRequestNew', RequestUs::where('status', 1)->count());
        view()->share('countRequestRead', RequestUs::where('status', 2)->count());
        
        view()->share('countContactUsNew', ContactUs::where('status', 1)->count());
        view()->share('countContactUsRead', ContactUs::where('status', 2)->count());
        
        view()->share('countDmcaNew', DMCA::where('status', 1)->count());
        view()->share('countDmcaRead', DMCA::where('status', 2)->count());

        view()->share('countTotalPost', Post::all()->count());
        view()->share('countTotalPage', Page::all()->count());
        view()->share('countTotalCategory', Category::all()->count());
        
        view()->share('countActivePage', Page::where('status', 1)->count());
        view()->share('countDeletedPage', Page::where('status', 2)->count());
        
        view()->share('countDraftPost', Post::where('status', 0)->count());
        view()->share('countActivePost', Post::where('status', 1)->count());
        view()->share('countDeletedPost', Post::where('status', 2)->count());

        view()->share('countCategory', Category::where('status', 1)->count());
        view()->share('countGenre', Genre::where('status', 1)->count());
        view()->share('countLanguage', Language::where('status', 1)->count());
        view()->share('countQuality', Quality::where('status', 1)->count());
        view()->share('countTypes', Posttype::where('status', 1)->count());
        
        view()->share('countNewComments', Comment::where(['status'=> 1, 'has_reply' => false])->count());
        view()->share('countRepliedComments', Comment::where(['status' => 1, 'has_reply' => true])->count());
        view()->share('countIgnoredComments', Comment::where('status', 2)->count());
        view()->share('countDeletedComments', Comment::where('status', 3)->count());
        
        view()->share('countAdmins', Admin::where('status', 1)->count());

    }
}
