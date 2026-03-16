<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Menampilkan halaman statis berdasarkan slug.
     * Slug yang didukung: kebijakan-privasi, ketentuan-layanan, tentang-kami, kontak, faq
     */
    public function show(string $slug)
    {
        $allowed = [
            'kebijakan-privasi' => ['title' => 'Kebijakan Privasi', 'view' => 'pages.privacy-policy'],
            'ketentuan-layanan' => ['title' => 'Ketentuan Layanan', 'view' => 'pages.terms-of-service'],
            'tentang-kami'      => ['title' => 'Tentang Kami', 'view' => 'pages.about'],
            'kontak'            => ['title' => 'Kontak', 'view' => 'pages.contact'],
            'faq'               => ['title' => 'Pertanyaan Umum', 'view' => 'pages.faq'],
        ];

        if (!isset($allowed[$slug])) {
            abort(404);
        }

        $config = $allowed[$slug];
        return view($config['view'], ['pageTitle' => $config['title']]);
    }
}
