<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class CourseController extends Controller
{
    /**
     * Fetch all courses with their cover image and related data
     * Endpoint: /api/courses
     */
    public function index()
    {
        // Get the Strapi URL and token from environment variables
        $strapiUrl = getenv('STRAPI_URL') . '/api/courses'; // Base URL for courses
        $token = getenv('STRAPI_TOKEN'); // Strapi API token

        $dateFilter = '2024-11-25T23:28:31.252Z';  

        // Get the HTTP client from the service
        $client = Services::curlrequest();

        // Make the request to the Strapi API
        $response = $client->get($strapiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $token ? "Bearer $token" : null, // Add the token if available
            ],
            'query' => [
                'filters' => [
                    'createdAt' => [  
                      '$gt' => $dateFilter // Filter for videos created before the specified date  
                    ]
                ],  
                'populate' => "*",
                // 'populate' => [
                    // 'Image' => '*', // Populate the cover image
            //         'modules' => [
            //             'populate' => 'videos', // Populate modules and their videos
            //         ],
                // ],
                'sort' => ['createdAt:desc'], // Sort courses by creation date in descending order
            ],
        ]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            // Return the response body as JSON
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } else {
            // Handle errors
            return $this->response->setStatusCode($response->getStatusCode())
                                  ->setJSON(['error' => 'Unable to fetch courses']);
        }
    }

    /**
     * Fetch a specific course by ID with its cover image, modules, and videos
     * Endpoint: /api/courses/{id}
     */
    public function show($id)
    {
        // Get the Strapi URL and token from environment variables
        $strapiUrl = getenv('STRAPI_URL') . "/api/courses/$id"; // URL for a specific course
        $token = getenv('STRAPI_TOKEN'); // Strapi API token

        // Get the HTTP client from the service
        $client = Services::curlrequest();



        // Make the request to the Strapi API
       $response = $client->get($strapiUrl, [
    'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => $token ? "Bearer $token" : null, // Add the token if available
    ],
    'query' => [
        'populate' => [
    'Image', // Populate the Image field
    'modules' => [ // Populate the modules relation
        'populate' => [ // Nested populate for modules
            'videos', // Populate the videos relation inside modules
            'quiz'
        ]
    ]
]
    ],
]);

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            // Return the response body as JSON
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } else {
            // Handle errors
            return $this->response->setStatusCode($response->getStatusCode())
                                  ->setJSON(['error' => 'Unable to fetch the course']);
        }
    }
}