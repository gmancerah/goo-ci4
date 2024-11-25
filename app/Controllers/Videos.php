<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class Videos extends Controller
{
  
  // http://your-codeigniter-url/api/videos?page=1&pageSize=10
  public function index()
  {
      // URL of your Strapi API endpoint
      

      // Get the HTTP client from the service
      $client = Services::curlrequest();


      $strapiUrl = getenv('STRAPI_URL') . '/api/videos'; // Get the Strapi URL from .env
      $token = getenv('STRAPI_TOKEN'); // Get the Strapi token from .env
      $defaultPageSize = getenv('DEFAULT_PAGE_SIZE') ?: 10; // Get the default page size from .env, default to 10 if not set


      
       $page = $this->request->getGet('page') ?? 1; // Current page
      $pageSize = $this->request->getGet('pageSize') ?? $defaultPageSize; // Number of items per page


      // Make the request to the Strapi API
       $response = $client->get($strapiUrl, [
          'headers' => [
              'Content-Type' => 'application/json',
              // Add the authorization header if a token is provided
              'Authorization' => $token ? "Bearer $token" : null,
          ],
          'query' => [
              'filters' => [
                  'video_asset' => [
                      'url' => [
                          '$contains' => '.mp4' // Filter for .mp4 files
                      ]
                  ]
              ],
              'pagination' => [
                  'page' => $page, // Current page
                  'pageSize' => $pageSize // Number of items per page
              ],
              'populate' => '*',
          ]
      ]);


      // Check if the request was successful
      if ($response->getStatusCode() === 200) {
          // Return the response body as JSON
          return $this->response->setJSON($response->getBody());
      } else {
          // Handle errors
          return $this->response->setStatusCode($response->getStatusCode())
                                 ->setJSON(['error' => 'Unable to fetch videos']);
      }
  }


  public function show($id)
  {
      // URL of your Strapi API endpoint for a specific video

  	  $strapiUrl = getenv('STRAPI_URL') . "/api/videos/".$id; // Get the Strapi URL from .env
      $token = getenv('STRAPI_TOKEN'); // Get the Strapi token from .env
      

      // Get the HTTP client from the service
      $client = Services::curlrequest();

      
      // Make the request to the Strapi API for the specific video
      $response = $client->get($strapiUrl, [
          'headers' => [
              // 'Content-Type' => 'application/json',
              // Add any authentication headers if required
               'Authorization' => $token ? "Bearer $token" : null,
          ]
      ]);

      // Check if the request was successful
      if ($response->getStatusCode() === 200) {
          // Return the response body as JSON
          return $this->response->setJSON($response->getBody());
      } else {
          // Handle errors
          return $this->response->setStatusCode($response->getStatusCode())
                                 ->setJSON(['error' => 'Unable to fetch the video']);
      }
  }


}