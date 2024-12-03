<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class QuizController extends Controller
{
    /**
     * Fetch all courses with their cover image and related data
     * Endpoint: /api/courses
     */
    public function index()
    {
        // Get the Strapi URL and token from environment variables
        $strapiUrl = getenv('STRAPI_URL') . '/api/quizzes'; // Base URL for courses
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
        $strapiUrl = getenv('STRAPI_URL') . "/api/quizzes/$id"; // URL for a specific course
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

    'quiz_questions', // Populate the Image field
    'quiz_questions.quiz_question_answers'
    
    
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