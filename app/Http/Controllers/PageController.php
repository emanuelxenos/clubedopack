<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function helpCenter()
    {
        return view('pages.help-center');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Simula envio de contato
        return back()->with('success', 'Mensagem enviada com sucesso! Nossa equipe entrará em contato em breve.');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }
}
