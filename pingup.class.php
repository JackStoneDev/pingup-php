<?php

    /**
     * pingup-php
     *
     * PHP wrapper for the Pingup Booking API
     *
     * @author Jack Stone
     *
     * @version 1.0.0
     *
     * @copyright Jack Stone 2014 on Github
     *
     * https://github.com/JackStoneDev/pingup-php
     *
     * Licensed under a MIT license.
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     * 
     * The above copyright notice and this permission notice shall be included in all
     * copies or substantial portions of the Software.
     * 
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
     * SOFTWARE.
     *
     */

    class pingup
    {
        const liveEndpoint = "https://api.pingup.com/";
        const sandboxEndpoint = "http://api.sandbox.pingup.com/";
        const apiVersion = "v1";
        
        private $token = null;
        private $endpoint = null;
        
        /**
         * Constructor function
         *
         * Stores access token for later API calls
         *
         * @param String $authToken The Pingup API access token
         * @param boolean $sandbox (optional) Will subsequent API calls happen in the sandboxed or live environment? Defaults to live environment (false)
         * 
         * @throws Exception if no access token is provided
         *
         * @return pingup
         */
         
        public function __construct($authToken, $sandbox = false)
        {
            if (isset($authToken))
            {
                $this->token = $authToken;
                
                if ($sandbox)
                    $this->endpoint = pingup::sandboxEndpoint;
                else $this->endpoint = pingup::liveEndpoint;
            }
            else throw new Exception("Pingup: No access token provided.");
        }
        
        /**
         * Generate access token for later API calls
         *
         * @param String $key API key provided to Pingup developer account
         * @param String $secret API secret provided to Pingup developer account
         * @param boolean $sandbox (optional) Was the API key provided to you for the sandboxed or live environment? Defaults to live environment (false)
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public static function generateTokens($key, $secret, $sandbox = false)
        {
            return pingup::authRequest("POST", http_build_query(Array("key" => $key, "secret" => $secret)), "auth/generateTokens", $sandbox);
        }
        
        /**
         * Obtain new token when access token expires
         *
         * @param String $refreshToken Refresh token issued when calling generateTokens()
         * @param String $secret API secret provided to Pingup developer account
         * @param boolean $sandbox (optional) Was the API key provided to you for the sandboxed or live environment? Defaults to live environment (false)
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
        
        public static function refreshToken($refreshToken, $secret, $sandbox = false)
        {
            return pingup::authRequest("PUT", http_build_query(Array("token" => $refreshToken, "secret" => $secret)), "auth/refreshToken", $sandbox);
        }
        
        /** 
         * Make HTTP requests to Pingup API for authentication
         * 
         * @param String $method HTTP method used (POST, GET, etc.)
         * @param String $parameters Parameters of HTTP request in query string format
         * @param String $path Path of API request on Pingup server
         * @param boolean $sandbox Is request going to occur in the sandboxed or live environment?
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public static function authRequest($method, $parameters, $path, $sandbox)
        {
            // Format endpoint
            if ($sandbox)
                $endpoint = "http://api.sandbox.pingup.com/";
            else $endpoint = "https://api.pingup.com/";
            
            $path .= "?$parameters";          

            // Make the request
            $array = json_decode(shell_exec("curl -L -X $method -H \"Content-type: application/json\" \"" . $endpoint . $path . "\""), true);
            
            // Was request successful?
            if (isset($array["httpStatus"]))
                throw new Exception("Pingup API error " . $array["responseReference"] . ": " . $array["message"] . " " . $array["description"]);
            else return $array;
        }
        
        /** 
         * Get list of places
         *
         * @param Array $requestParams (optional) Associative array of request parameters used to search for places.  Possible values are offset, limit, name, street, locality, region, postCode, country, latitude, longitude, radius, category, modifiedSince, factualId, and userId.  See the documentation for an in-depth explanation of all parameters
         *
         * @throws Exception if HTTP request fails
         * 
         * @return Array
         */
         
        public function getPlaces($requestParams = Array())
        {
            return $this->request("GET", http_build_query($requestParams), "places");
        }
        
        /** 
         * Get a place listing from its unique ID
         * 
         * @param String $placeId The unique ID of the place
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function getPlace($placeId)
        {
            return $this->request("GET", "", "places/$placeId");
        }
        
        /**
         * Get list of services for a specific place
         *
         * @param String $placeId The unique ID of the place
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
        
        public function getServicesForPlace($placeId)
        {
            return $this->request("GET", "", "places/$placeId/services");
        }
        
        /**
         * Get list of personnel for a specific service
         *
         * @param String $placeId The unique ID of the place
         * @param String $serviceId The unique ID of the service
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function getPersonnelForService($placeId, $serviceId)
        {
            return $this->request("GET", "", "places/$placeId/services/$serviceId/personnel");
        }
        
        /**
         * Get available time slots for a specific service
         *
         * @param String $placeId The unique ID of the place
         * @param String $serviceId The unique ID of the service
         * @param String $startTime The first possible date for the listing of time slots, inclusive (yyyy-mm-dd)
         * @param String $endTime The last possible date for the listing of time slots, inclusive (yyyy-mm-dd).  Difference between $startTime and $endTime cannot be greater than seven days
         * @param String $personnelId (optional) The unique ID of the desired personnel performing the service.  Leave blank for no preference
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function getTimeSlotsForService($placeId, $serviceId, $startTime, $endTime, $personnelId = "")
        {
            return $this->request("GET", http_build_query(Array("startTime" => $startTime, "endTime" => $endTime, "personnelId" => $personnelId)), "places/$placeId/services/$serviceId/timeSlots");
        }
        
        /**
         * Create a new user
         *
         * @param String $firstName The desired first name of the user
         * @param String $lastName The desired last name of the user
         * @param String $phoneNumber The desired phone number of the user
         * @param String $email The desired email address of the user
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function createUser($firstName, $lastName, $phoneNumber, $email)
        {
            return $this->request("POST", http_build_query(Array("firstName" => $firstName, "lastName" => $lastName, "phoneNumber" => $phoneNumber, "email" => $email), "users"));
        }
        
        /**
         * Get user information from his or her unique ID
         *
         * @param String $userId The unique ID of the user
         * 
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function getUser($userId)
        {
            return $this->request("GET", "", "users/$userId");
        }
        
        /**
         * Edit a user
         *
         * @param String $userId The unique ID of the user
         * @param Array $requestParams (optional) Associative array of request parameters used to edit the user.  Possible values are firstName, lastName, phoneNumber, and email.  See the documentation for an in-depth explanation of all parameters
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function editUser($userId, $requestParams = Array())
        {
            $parameters["userId"] = $userId;
            
            return $this->request("PUT", http_build_query($requestParams), "users/$userId");
        }
        
        /**
         * Delete a user
         *
         * @param String $userId The unique ID of the user
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function deleteUser($userId)
        {
            return $this->request("DELETE", "", "users/$userId");
        }
        
        /** 
         * Create an appointment
         *
         * @param String $placeId The unique ID of the place where the appointment will be booked
         * @param String $serviceId The unique ID of the service being requested
         * @param String $userId The unique ID of the user booking the appointment
         * @param Array $timeSlot Associative array of the time slot information.  See the documentation for an in-depth explanation of how to format this array
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
         
        public function createAppointment($placeId, $serviceId, $userId, $timeSlot)
        {
            return $this->request("POST", http_build_query(Array("placeId" => $placeId, "serviceId" => $serviceId, "userId" => $userId, "timeSlot" => $timeSlot)), "appointments");
        }
        
        /**
         * Get a list of appointments for a specific user
         *
         * @param String $userId The unique ID of the user
         * @param Array $requestParams (optional) Associative array of request parameters used for search.  Possible values are offset, limit, status, dateFrom (yyyy-mm-dd), and dateTo (yyyy-mm-dd).  See the documentation for an in-depth explanation of all parameters
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
        
        public function getAppointments($userId, $requestParams = Array())
        {
            $parameters["userId"] = $userId;
            
            return $this->request("GET", http_build_query($requestParams), "appointments");
        }
        
        /**
         * Get the status of an appointment from its unique ID
         *
         * @param String $appointmentId The unique ID of the appointment
         * @param String $userId The unique ID of the user who has booked the appointment
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
        
        public function getAppointmentStatus($appointmentId, $userId)
        {
            return $this->request("GET", http_build_query(Array("userId" => $userId)), "appointments/$appointmentId");
        }
        
        /**
         * Delete an appointment
         *
         * @param String $appointmentId The unique ID of the appointment
         * @param String $userId The unique ID of the user who has booked the appointment
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
        
        public function deleteAppointment($appointmentId, $userId)
        {
            return $this->request("DELETE", http_build_query(Array("userId" => $userId)), "appointments/$appointmentId");
        }
        
        /**
         * Make HTTP requests to Pingup API
         *
         * @param String $method HTTP method used (POST, GET, etc.)
         * @param String $parameters Parameters of HTTP request in query string format
         * @param String $path Path of API request on Pingup server
         *
         * @throws Exception if HTTP request fails
         *
         * @return Array
         */
          
        protected function request($method, $parameters, $path)
        {            
            $path .= "?$parameters";            

            // Make the request
            $array = json_decode(shell_exec("curl -L -X $method -H \"Token: " . $this->token . "\" -H \"Content-type: application/json\" \"" . $this->endpoint . pingup::apiVersion . "/" . $path . "\""), true);
            
            // Was request successful?
            if (isset($array["httpStatus"]))
                throw new Exception("Pingup API error " . $array["responseReference"] . ": " . $array["message"] . " " . $array["description"]);
            else return $array;
        }
    }

?>