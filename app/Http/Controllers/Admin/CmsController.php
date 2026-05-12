<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingPage;
use Illuminate\Support\Facades\Log;

class CmsController extends Controller
{
    public function landing()
    {
        $settings = LandingPage::pluck('content', 'section_key')->toArray();
        return view('admin.cms.landing', compact('settings'));
    }

    public function updateLanding(Request $request)
    {
        $data = $request->except(['_token']);
        
        // Handle hero slides specifically because of file uploads
        if (isset($data['hero_slides']) && is_array($data['hero_slides'])) {
            $slides = [];
            foreach ($data['hero_slides'] as $index => $slide) {
                $imageUrl = $slide['existing_image'] ?? null;
                
                if (isset($slide['image']) && $slide['image'] instanceof \Illuminate\Http\UploadedFile) {
                    try {
                        $imageUrl = \App\Services\Cloudinary::upload($slide['image']->getRealPath())->getSecurePath();
                    } catch (\Exception $e) {
                        Log::error('Cloudinary upload failed: ' . $e->getMessage());
                    }
                }
                
                $slides[] = [
                    'badge' => $slide['badge'] ?? '',
                    'title' => $slide['title'] ?? '',
                    'subtitle' => $slide['subtitle'] ?? '',
                    'image' => $imageUrl,
                ];
            }
            LandingPage::updateOrCreate(['section_key' => 'hero_slides'], ['content' => $slides]);
            unset($data['hero_slides']);
        } else {
             // If hero slides is empty but we submitted the form, we might want to clear it or leave it
             if ($request->has('hero_slides_submitted')) {
                  LandingPage::updateOrCreate(['section_key' => 'hero_slides'], ['content' => []]);
                  unset($data['hero_slides_submitted']);
             }
        }

        // Handle arrays like faqs and how_it_works
        if (isset($data['faqs'])) {
            LandingPage::updateOrCreate(['section_key' => 'faqs'], ['content' => array_values($data['faqs'])]);
            unset($data['faqs']);
        }
        
        if (isset($data['how_it_works'])) {
            LandingPage::updateOrCreate(['section_key' => 'how_it_works'], ['content' => array_values($data['how_it_works'])]);
            unset($data['how_it_works']);
        }

        // Handle regular text fields
        foreach ($data as $key => $value) {
            LandingPage::updateOrCreate(['section_key' => $key], ['content' => $value]);
        }

        \Illuminate\Support\Facades\Cache::forget('home:cmsSettings');

        return redirect()->route('admin.cms.landing')->with('success', 'Landing page settings updated successfully.');
    }
}
