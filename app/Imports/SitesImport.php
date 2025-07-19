<?php

namespace App\Imports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\ToModel;

class SitesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip if domain is empty
        if (empty($row[0])) {
            return null;
        }

        // Clean the domain (remove http://, https://, www.)
        $domain = $this->cleanDomain($row[0]);

        // Check if the domain already exists
        $existingSite = Site::where('domain', $domain)->first();

        // If the domain exists, ignore this row
        if ($existingSite) {
            return null;
        }
        // Process price columns (remove currency symbols and extract numeric values)
        $price_guest_posting = $this->cleanPrice($row[2]);
        $price_link_insertion = $this->cleanPrice($row[3]);

        // Return a new Site model instance
        return new Site([
            'domain' => $domain,
            'currency' => $row[1],
            'price_guest_post' => $price_guest_posting,
            'price_link_insertion' => $price_link_insertion,
            'niche' => $row[4],
        ]);
    }
    
    /**
     * Remove http://, https://, and www. from the domain.
     *
     * @param string $url
     * @return string
     */
    private function cleanDomain(string $url): string
    {
        // Remove http:// or https://
        $url = preg_replace('#^https?://#', '', $url);

        // Remove www.
        $url = preg_replace('#^www\.#', '', $url);

        // Remove trailing slashes
        $url = rtrim($url, '/');

        return $url;
    }

    /**
     * Remove currency symbols and extract numeric value from price.
     *
     * @param mixed $price
     * @return float|null
     */
    private function cleanPrice($price)
    {
        // Return null if price is null or an empty string
        if (is_null($price) || $price === '') {
            return null;
        }

        // If price is already a number, return it directly
        if (is_numeric($price)) {
            return (float)$price;
        }

        // Remove all non-numeric characters except dots (for decimals)
        $cleanedPrice = preg_replace('/[^\d.]/', '', (string)$price);

        // Return null if the cleaned result is empty or not numeric
        return $cleanedPrice !== '' && is_numeric($cleanedPrice) 
            ? (float)$cleanedPrice 
            : null;
    }
}