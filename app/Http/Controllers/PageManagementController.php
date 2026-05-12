<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\cmsSection;
use App\Support\MediaUrl;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageManagementController extends Controller
{
    public function index()
    {
        $cards = [
            [
                'title' => 'Contact Configuration',
                'button' => 'Manage Contact Page',
                'link' => route('cms.contact'),
            ],
            [
                'title' => 'Marketplace & Database',
                'button' => 'Manage Marketplace & Database',
                'link' => route('cms.marketplace'),
            ],
            [
                'title' => 'GalleryHub',
                'button' => 'Manage GalleryHub',
                'link' => route('cms.gallery_hub'),
            ],
            [
                'title' => 'Art Consultancy',
                'button' => 'Manage Art Consultancy',
                'link' => route('cms.art_consultancy'),
            ],
            [
                'title' => 'Terms & Conditions',
                'button' => 'Manage Terms & Conditions',
                'link' => route('cms.terms'),
            ],
            [
                'title' => 'Privacy Policy',
                'button' => 'Manage Privacy Policy',
                'link' => route('cms.privacy_policy'),
            ],
            [
                'title' => 'About ADAI',
                'button' => 'Manage About ADAI',
                'link' => route('cms.about_adai'),
            ],
        ];

        return view('cms.index', compact('cards'));
    }

    public function contact()
    {
        $content = CmsPage::where('page_title', 'contact_information')->where('section', 'contact')->first();
        $content = json_decode(@$content->content, true);
        return view('cms.contact', compact('content'));
    }

    public function contactSave(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email',
            'phone_number' => 'required',
            'office_location' => 'required',
        ]);

        $data['email_address'] = $request->email_address;
        $data['phone_number'] = $request->phone_number;
        $data['office_location'] = $request->office_location;

        $cmsPage = CmsPage::updateOrCreate(
            ['page_title' => 'contact_information', 'section' => 'contact'],
            ['content' => json_encode($data)]
        );

        // Here you can handle the validated data, e.g., save it to the database or send an email.

        return redirect()->back()->with('success', 'Contact information saved successfully!');
    }

    public function terms()
    {
        $cmsPage = CmsPage::where('page_title', 'terms_condition')->first();
        if ($cmsPage) {
            $content = $cmsPage->content;
        } else {
            $content = '';
        }

        return view('cms.terms', compact('content'));
    }

    public function termsSave(Request $request)
    {
        $request->validate([
            'content_html' => 'required',
        ]);

        $cmsPage = CmsPage::updateOrCreate(
            ['page_title' => 'terms_condition'],
            ['content' => $request->content_html]
        );

        // Here you can handle the validated data, e.g., save it to the database or send an email.

        return redirect()->back()->with('success', 'Terms and Condition saved successfully!');
    }

    public function marketplace()
    {
        $cmsPage = CmsPage::where('page_title', 'marketplace')->first();
        if ($cmsPage) {
            $data = json_decode($cmsPage->content, true);
            $data['apart']['featured'] = array_values($data['apart']['featured']);
            // dd($data);
        } else {
            $data = [
                'hero' => [
                    'tag_1' => '',
                    'tag_2' => '',
                    'main_title' => '',
                    'subtitle' => '',
                ],
                'overview' => [
                    'section_label' => '',
                    'headline' => '',
                    'body_text' => '',
                    'subtitle' => '',
                ],
                'apart' => [
                    'section_title' => '',
                    'featured' => []
                ]
            ];
        }

        return view('cms.marketplace', compact('data'));
    }


    public function marketplaceSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'hero.tag_1' => 'required|string|max:255',
            'hero.tag_2' => 'required|string|max:255',
            'hero.main_title' => 'required|string|max:255',
            'hero.subtitle' => 'required|string|max:255', // optional
        ]);

        $data['hero'] = $request->hero;
        $data['overview'] = $request->overview;
        $data['apart']['section_title'] = $request->apart['section_title'];

        $upload_path = public_path('cms/marketplace_images');
        // Create folder if it doesn't exist
        if (!File::exists($upload_path)) {
            File::makeDirectory($upload_path, 0777, true, true);
        }

        foreach ($request->apart['featured'] as $featured) {

            // Ensure order exists; fallback to index if empty
            $order = $featured['order'] ?? null;

            if ($order === null || $order === '') {
                continue; // skip invalid items
            }

            // Store all non-file data first
            $data['apart']['featured'][$order] = $featured;

            // Handle image upload safely
            if (!empty($featured['image']) && $featured['image'] instanceof \Illuminate\Http\UploadedFile) {

                $file = $featured['image'];
                $filename = 'marketplace-' . time() . '-' . $order . '.' . $file->getClientOriginalExtension();

                // Move file
                $file->move($upload_path, $filename);

                // Save image path
                $data['apart']['featured'][$order]['image'] = 'cms/marketplace/' . $filename;
            } else {
                // Keep existing image if no new image is uploaded
                $data['apart']['featured'][$order]['image'] = $featured['image'] ?? null;
            }
        }

        $cmsPage = CmsPage::updateOrCreate(
            ['page_title' => 'marketplace'],
            ['content' => json_encode($data)]
        );

        // Here you can handle the validated data, e.g., save it to the database or send an email.

        return redirect()->back()->with('success', 'Terms and Condition saved successfully!');
    }

    public function galleryHub()
    {   $sectionData = CmsSection::where('page', 'gallery_hub')
            ->where('section', 'hero')
            ->first();
         $filed = $sectionData?->filed ?? '';
         $fields = array_map('trim', explode(',', $filed));
        $content = CmsPage::where('page_title', 'gallery_hub')->where('section', 'hero')->first();
        $content = json_decode(@$content->content, true);
        $section = 'hero';
        $page = "gallery_hub";
        return view('cms.gallery-hub', compact('content', 'section', 'page','fields'));
    }

    public function adaiGalleriesHeroSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'hero.tag_1' => 'required|string|max:255',
            'hero.tag_2' => 'required|string|max:255',
            'hero.main_title' => 'required|string|max:255',
            'hero.subtitle' => 'required|string|max:255', // optional
        ]);

        $data['hero'] = $request->hero;

        $cmsPage = CmsPage::updateOrCreate(
            ['page_title' => 'adai_galleries'],
            ['content' => json_encode($data)]
        );

        // Here you can handle the validated data, e.g., save it to the database or send an email.

        return redirect()->back()->with('success', 'Adai Galleries Hero Section saved successfully!');
    }

    public function manageCoreService()
    {
        $content = CmsPage::where('page_title', 'art_consultancy')->where('section', 'core_services')->first();
        $services = json_decode($content->content, true);
    }

    public function storeCoreService(Request $request)
    {
        $request->validate([
            'header_title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',

            'services' => 'required|array|min:1|max:4',

            'services.*.title' => 'required|string|max:255',
            'services.*.alt' => 'nullable|string|max:255',
            'services.*.description' => 'nullable|string',
            'services.*.order' => 'nullable|integer',
            'services.*.image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $servicesData = [];

        foreach ($request->services as $index => $service) {
            $singleService = [
                'title' => $service['title'],
                'alt' => $service['alt'] ?? null,
                'description' => $service['description'] ?? null,
                'order' => $service['order'] ?? $index + 1,
            ];

            // new image uploaded?
            if (isset($service['image']) && $service['image']) {
                $file = $service['image'];
                $filename = 'core-service-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('cms/services'), $filename);

                $singleService['image'] = 'cms/services/' . $filename;
            } else {
                // keep old image
                $singleService['image'] = $service['old_image'] ?? null;
            }

            $servicesData[] = $singleService;
        }

        // Save everything into cms_pages
        CmsPage::updateOrCreate(
            ['page_title' => 'art_consultancy', 'section' => 'core_services'],
            [
                'content' => json_encode([
                    'header_title' => $request->header_title,
                    'subtitle' => $request->subtitle,
                    'services' => $servicesData,
                ])
            ]
        );

        return response()->json([
            'status_code' => 200,
            'type' => 'success',
            'error' => false,
            'message' => 'Save successfully.',
            'showTost' => true,
            'reset' => true,
            'url' => route('cms.art-consultancy'),
        ], 200);
    }

    public function manageSection($page, $section)
    {

        $sectionData = CmsSection::where('page', $page)
            ->where('section', $section)
            ->first();
         $filed = $sectionData?->filed ?? '';
         $fields = array_map('trim', explode(',', $filed));
        if ($section == 'overview' || $section === 'why_adai') {
              $viewPath = "cms.sections.$section";
        } else {
            $viewPath = "cms.sections.commonSection";
        }


        if (!view()->exists($viewPath)) {
            abort(404, "Section view not found");
        }

        // Load CMS data for this page + section
        $getData = CmsPage::where([
            'page_title' => $page,
            'section' => $section,
        ])->first();

        $content = $getData ? json_decode($getData->content, true) : [];

        return view($viewPath, compact('content', 'page', 'section','fields'));
    }
    public function itemBlock(Request $request, $type)
    {
        $index = $request->index;
        
        $sectionData = CmsSection::where('section', $type)
            ->first();
        $filed = $sectionData?->filed ?? '';
        $fields = array_map('trim', explode(',', $filed));
        $view = 'cms.partials.item_with_row';
        if ($type == 'philanthropy_cultural_legacy' || $type == 'bespoke_client_services' || $type == 'mission_vision_technology') {
            $description = true;
        } else {
            $description = false;
        }
        return view($view, [
            'i' => $index,
            'service' => [],
            'description' => $description,
            'fields' =>$fields
        ]);
    }
    public function artConsultancy()
    {
          $sectionData = CmsSection::where('page', 'art_consultancy')
            ->where('section', 'hero')
            ->first();
         $filed = $sectionData?->filed ?? '';
         $fields = array_map('trim', explode(',', $filed));
        $content = CmsPage::where('page_title', 'art_consultancy')->where('section', 'hero')->first();
        $content = json_decode(@$content->content, true);
        $section = 'hero';
        $page = "art_consultancy";
        return view('cms.art-consultancy', compact('content', 'section', 'page','fields'));
    }
    public function aboutAdai()
    {
        $sectionData = CmsSection::where('page', 'about_adai')
            ->where('section', 'hero')
            ->first();
         $filed = $sectionData?->filed ?? '';
         $fields = array_map('trim', explode(',', $filed));
        $content = CmsPage::where('page_title', 'about_adai')->where('section', 'hero')->first();
        $content = json_decode(@$content->content, true);
        $section = 'hero';
        $page = "about_adai";
        return view('cms.about-adai', compact('content', 'section', 'page','fields'));
    }
    public function storeSection(Request $request, $section)
    {
        // Validate common fields
        $pageName = $request->page_title;


        if ($request->header_title) {
            $rules['subtitle'] = 'required|string|max:255';
        }
        if ($request->subtitle) {
            $rules['subtitle'] = 'required|string|max:255';
        }

        if (!$request->old_image) {
            // No old image → main_image required only if field exists
            $rules['main_image'] = 'sometimes|required|image';
        }
        if (!$request->old_digital_brochure) {
            $rules['digital_brochure'] = 'sometimes|required';
        }

        $request->validate($rules);
        if ($request->has('services')) {
            $request->validate([
                'services' => 'array|min:1|max:10',
                'services.*.title' => 'required|string|max:255',
                'services.*.description' => 'nullable|string',
            ]);
        }

        $servicesData = [];
        if (@$request->services) {
            foreach ($request->services as $index => $service) {
                $singleService = [
                    'title' => $service['title'],
                    'description' => $service['description'] ?? null, // <-- move here (always saves)
                ];

                // If section requires image
                if (in_array($section, ['core_services', 'adai_core_service','gallery_hub_section','galleryHub_why_adai'])) {

                    if (isset($service['image']) && $service['image']) {
                        $file = $service['image'];
                        $filename = $section . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                        $path = "uploads/cms/$section/$filename";
                        Storage::disk('azure')->put($path, file_get_contents($file), 'public');
                        $singleService['image'] =  $path;
                    } else {
                        $singleService['image'] = $service['old_image'] ?? null;
                    }
                }
                $singleService['alt'] = $service['alt'] ?? null;
                $singleService['type'] = $service['type'] ?? null;
                $singleService['order'] = $service['order'] ?? $index + 1;
                $singleService['bullets'] = $service['bullets'] ?? [];

                $servicesData[] = $singleService;
            }
        }

        // Main image only for some sections
        $mainImage = null;

        if ($request->hasFile('main_image')) {
            $image      = $request->file('main_image');
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = "uploads/cms/$section/$imageName";
            Storage::disk('azure')->put($path, file_get_contents($image), 'public');
            $mainImage = $path;
        } else {
            $mainImage = $request->input('old_image') ?? null;
        }
        if ($request->hasFile('digital_brochure')) {

            // Delete old file from Azure if exists
            if ($request->old_digital_brochure) {
                $oldPath = str_replace(config('filesystems.disks.azure.base_url') . '/', '', $request->old_digital_brochure);
                if (Storage::disk('azure')->exists($oldPath)) {
                    Storage::disk('azure')->delete($oldPath);
                }
            }

            // Get uploaded file
            $file = $request->file('digital_brochure');

            // Create unique file name
            $fileName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Optional folder
            $folder = "cms/$section";

            // Upload to Azure
            $path = $file->storeAs($folder, $fileName, 'azure');

            // Get full URL
            $digital_brochure = $folder . "/" . $fileName;
        } else {
            $digital_brochure = $request->input('old_digital_brochure') ?? null;
        }

        // Save to DB
        CmsPage::updateOrCreate(
            ['page_title' => $request->page_title, 'section' => $section],
            [
                'content' => json_encode([
                    'main_image'   => $mainImage,
                    'background_alt_text' => $request->background_alt_text ?? null,
                    'digital_brochure' => $digital_brochure,
                    'main_content' => $request->main_content,
                    'services'     => $servicesData,
                ])
            ]
        );
        return response()->json([
            'status_code' => 200,
            'type' => 'success',
            'error' => false,
            'message' => 'Save successfully.',
            'showTost' => true,
            'reset' => true,
            'url' => route("cms.$pageName"),
        ], 200);
    }
    public function privacyPolicy()
    {
        $content = CmsPage::where('page_title', 'privacy_policy')->where('section', 'privacy_policy')->first();
        $content = json_decode(@$content->content, true);
        $section = 'privacy_policy';
        $page = "privacy_policy";
        return view('cms.sections.privacy', compact('content', 'section', 'page'));
    }
}
