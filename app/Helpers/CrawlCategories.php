<?php


namespace App\Helpers;


use App\Http\Controllers\Api\Admin\CategoryController;
use App\Models\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class CrawlCategories {

    public static function getCategories () {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://api.trustpilot.com/v1/categories?locale=en-US', [
                'headers' => [
                    'Authorization' => 'Bearer 9pg9ssuS94nVYqlHaWOjss2Ckqrg',
                    'Host' => 'api.trustpilot.com',
                    'ApiKey' => "nZkt0UMZP2MeF99AOcviMZDmIfiI2L0x"
                ]
            ]);
            $body = $response->getBody();
            $categories = json_decode($body, true);
            $parent_lv0 = [];
            foreach ($categories['categories'] as $key => $item) {
                if (empty($item['parentId'])) {
                    $category = self::saveToCategory($item, 0);
                    if (!empty($item["childrenCategories"])) {
                        foreach ($categories['categories'] as $key2 => $item2) {
                            if (in_array($item2['categoryId'], $item['childrenCategories'])) {
                                $category_lv2 = self::saveToCategory($item2, $category->id);

                                if (!empty($item2["childrenCategories"])) {
                                    foreach ($categories['categories'] as $key3 => $item3) {
                                        if (in_array($item3['categoryId'], $item2['childrenCategories'])) {
                                            $category_lv3 = self::saveToCategory($item3, $category_lv2->id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (GuzzleException $e) {
        }

    }

    public static function saveToCategory ($item, $parent_id) {
        $category = new Category();
        $category->parent_id = $parent_id;
        $category->status = 1;
        $category->name = $item['displayName'];
        $category->slug = Str::slug($item['name']);
        $category->created_by = 11;
        $category->updated_by = 11;
        $category->save();
        $categoryCtrl = new CategoryController();
        $categoryCtrl->_updateParent($category->id, $category->parent_id);
        return $category;
    }
}
