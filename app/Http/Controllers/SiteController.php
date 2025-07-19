<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SitesImport;
use App\Exports\SitesExport;
use App\Models\Site;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteController extends Controller
{
    public function import(Request $request)
    {
        // Validate file input
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        // Import the file
        Excel::import(new SitesImport, $request->file('file'));

        activity()->log('Sites imported from Excel file.');


        return redirect()->back()->with('success', 'File imported successfully.');
    }

    public function showUploadForm()
    {
        activity()->log('Viewed upload form.');
        return view('sites.upload');
    }

    public function index(Request $request)
    {
        $query = Site::query();
        $sortBy = $request->input('sort_by', 'domain');  // default sort by domain
        $sortOrder = $request->input('sort_order', 'asc');  // default order ascending
    
        // Apply filters only if the parameter is present and not empty
        if ($request->has('domain') && $request->domain != '') {
            $query->where('domain', 'like', '%' . $request->domain . '%');
        }
    
        if ($request->has('currency') && $request->currency != '') {
            $query->where('currency', $request->currency);
        }
    
        if ($request->has('niche') && $request->niche != '') {
            $query->where('niche', 'like', '%' . $request->niche . '%');
        }
    
        if ($request->has('price_guest_post') && $request->price_guest_post != '') {
            $query->where('price_guest_post', '<=', $request->price_guest_post);
        }
        
    
        // Paginate the results
        $sites = $query->orderBy($sortBy, $sortOrder)->paginate(10);
        
        if ($request->ajax()) {
            return view('sites.partials.site-list', compact('sites'));  // Return only the table
        }

        activity()->log('Visited sites index page with filters.');
        return view('sites.index', compact('sites'));
    }


    public function applyRuleBlade()
    {
        activity()->log('Opened the rules blade.');
        return view('sites.rules');
    }
    public function applyRules(Request $request)
    {
        // Fetch input values
        $minPrices = $request->input('min_price');
        $maxPrices = $request->input('max_price');
        $incrementValues = $request->input('increment_value');

        $seoSites = Site::all();

        foreach ($seoSites as $site) {
            // Loop through all defined rules
            for ($i = 0; $i < count($minPrices); $i++) {
                $minPrice = $minPrices[$i];
                $maxPrice = $maxPrices[$i] ?? null; // Handle max as null for infinite range
                $incrementValue = $incrementValues[$i];

                // Check if the site's price falls in the rule's range
                if ($site->price_guest_post >= $minPrice && 
                    ($maxPrice === null || $site->price_guest_post <= $maxPrice)) {
                    // Apply the rule to guest post and link insertion prices
                    // Apply the increment to guest post and link insertion prices
                    $newGuestPostPrice = $site->price_guest_post + $incrementValue;
                    $newLinkInsertionPrice = $site->price_link_insertion + $incrementValue;
    
                    // Round the new prices to the nearest tenth
                    $roundedGuestPostPrice = $this->roundToNearestTenth($newGuestPostPrice);
                    $roundedLinkInsertionPrice = $this->roundToNearestTenth($newLinkInsertionPrice);
    
                    // Set the rounded sale prices
                    $site->sale_price_guest_post = $roundedGuestPostPrice;
                    $site->sale_price_link_insertion = $roundedLinkInsertionPrice;                
                    $site->save();
                }
            }
        }

        activity()->log('Applied pricing rules to all sites.');
        return redirect()->back()->with('success', 'Rules applied successfully');
    }

    /**
     * Round the given price to the nearest tenth.
     * E.g., 61 -> 60, 68 -> 70, 65 -> 70, 205 -> 210
     *
     * @param float|int $price
     * @return float|int
     */
    private function roundToNearestTenth($price)
    {
        return round($price / 10) * 10;
    }

    public function exportExcel(Request $request)
    {
        // Get the selected columns from the form
        $columns = $request->input('columns', ['domain', 'currency', 'price_guest_post', 'price_link_insertion', 'niche']);
        
        // Get the filters from the request (if any)
        $filters = [];
        if ($request->has('filters')) {
            parse_str($request->input('filters'), $filters);  // Convert query string into array
        }
    
        // Query the sites based on filters
        $query = Site::query();
    
        if (isset($filters['domain'])) {
            $query->where('domain', 'like', '%' . $filters['domain'] . '%');
        }
        if (isset($filters['currency'])) {
            $query->where('currency', $filters['currency']);
        }
        if (isset($filters['niche'])) {
            $query->where('niche', 'like', '%' . $filters['niche'] . '%');
        }
        if (isset($filters['price_guest_post'])) {
            $query->where('price_guest_post', '<=', $filters['price_guest_post']);
        }
    
    
        // Get the filtered data
        $sites = $query->get($columns);  // Select only the specified columns
        activity()->log('Exported sites to Excel with filters.');
        // Pass the filtered data and selected columns to the export class
        return Excel::download(new SitesExport($sites, $columns), 'filtered_sites.xlsx');
    }

    public function showDomainMatchingForm()
    {
        activity()->log('Opened domain matching form.');
        return view('sites.domain-matching');
    }

    public function processDomainMatching(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
    
        // Get domains from uploaded file
        $domains = $this->extractDomainsFromExcel($request->file('excel_file'));
        
        // Find matching domains in database
        $matches = Site::whereIn('domain', $domains)->get();
        
        // Store matches in session for export
        session(['domain_matches' => $matches]);
        
        activity()->log("Processed domain matching for " . count($domains) . " uploaded domains.");

        return view('sites.domain-matching-results', [
            'matches' => $matches,
            'totalDomains' => count($domains),
            'matchedCount' => $matches->count()
        ]);
    }
    
    public function exportDomainMatches(Request $request)
    {
        // Get the stored matches from session
        $matches = session('domain_matches');
        
        if (!$matches) {
            return back()->with('error', 'No matching data found to export');
        }
    
        // Get selected columns from the request, or use default columns
        $columns = $request->input('columns', [
            'domain', 'currency', 'price_guest_post', 
            'sale_price_guest_post', 'price_link_insertion', 
            'sale_price_link_insertion', 'niche'
        ]);
    
        activity()->log("Exported matched domains to Excel.");
        
        return Excel::download(new SitesExport($matches, $columns), 'domain_matches.xlsx');
    }
        
    private function extractDomainsFromExcel($file)
    {
        $data = Excel::toArray([], $file);
        $domains = [];
        
        // Assuming domains are in first column (adjust as needed)
        foreach ($data[0] as $row) {
            if (!empty($row[0])) {
                $domains[] = $this->normalizeDomain($row[0]);
            }
        }
        
        return array_unique($domains);
    }

    private function normalizeDomain($domain)
    {
        // Remove protocol and www for consistent matching
        $domain = str_replace(['http://', 'https://', 'www.'], '', $domain);
        
        // Remove trailing slashes and whitespace
        $domain = trim($domain, "/ \t\n\r\0\x0B");
        
        return $domain;
    }
}
