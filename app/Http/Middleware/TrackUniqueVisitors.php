<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUniqueVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $ipAddress = $request->ip();
    $userAgent = $request->header('User-Agent');
    $referrer = $request->headers->get('referer');
    $landingPage = $request->url();
    $timeOfVisit = now();

    $existingVisitor = Visitor::where('ip_address', $ipAddress)
        ->where('user_agent', $userAgent)
        ->first();

    if (!$existingVisitor) {
        $isNewVisitor = true;
        $existingVisitor = Visitor::create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referrer' => $referrer,
            'landing_page' => $landingPage,
            'time_of_visit' => $timeOfVisit,
            'time_spent' => 0,
            'page_views' => 1,
        ]);
    } else {
        $isNewVisitor = false;
        $existingVisitor->page_views++;

        // Calculate time spent by the visitor
        $timeSpent = now()->diffInHours($existingVisitor->time_of_visit);
        $existingVisitor->time_spent += $timeSpent;

        // Update the time of visit to the current time
        $existingVisitor->time_of_visit = $timeOfVisit;
        
        $existingVisitor->save();
    }

    // Check if the visitor is a returning visitor
    $isReturningVisitor = false;
    if ($request->hasCookie('visitor_id')) {
        $isReturningVisitor = true;
    } else {
        // Set a cookie for the visitor to identify them as returning
        $response = $next($request);
        $response->withCookie(cookie('visitor_id', $existingVisitor->id, 2628000)); // Cookie lasts for 30 days
        return $response;
    }

    // Add the 'is_returning_visitor' flag to the existingVisitor model
    $existingVisitor->is_returning_visitor = $isReturningVisitor;

    // Increment page_views for new requests from the returning visitor
    if ($isReturningVisitor) {
        $existingVisitor->page_views++;
        $existingVisitor->save();
    }

    return $next($request);
}

}
