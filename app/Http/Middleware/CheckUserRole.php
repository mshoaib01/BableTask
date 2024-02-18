<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
        if (!Auth::check()) {
            // If the user is not logged in, redirect them to login
            return redirect('login');
        }
       
        $user = Auth::user();

        // Check if the request method is DELETE and if the user is not an admin
        if ($request->isMethod('delete') && $user->user_role !== 'Admin') {
            // If the user tries to delete but is not an admin, deny access
            return redirect()->route('tasks.list')->with('error', 'You do not have rights to delete Task.');
            //return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
