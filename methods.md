#pingup-php documentation

All methods are designed to be as easy as possible to use.  Required request parameters are passed in via function arguments, and optional parameters are passed in as associative arrays for the greatest flexibility.  Results are returned in associative array format, but please note that the example responses on this page are written as JSON for the sake of readability.

##Table of contents
 - [Generating an access token](#generating-an-access-token)
 - [Retrieving places](#retrieving-places)
 - [Retrieving services](#retrieving-services)
 - [Retrieving personnel](#retrieving-personnel)
 - [Retrieving time slots](#retrieving-time-slots)
 - [Managing users](#managing-users)
 - [Managing appointments](#managing-appointments)

##Generating an access token

When registering for a Pingup developer account, you are given an API key and an API secret.  To make API calls, you must first generate an access token, either for the sandboxed API environment (where no real appointments can be booked) or for the live environment.  Leave the second argument blank to default to the live API environment, or pass in true for the sandboxed environment.  Note: The API key and secret that you receive are for one environment only; attempting to generate an access token for the other will fail.


```php
// Generate access token for the live environment
pingup::generateTokens("YOUR-API-KEY", "YOUR-API-SECRET");
// Generate access token for the sandboxed environment
pingup::generateTokens("YOUR-API-KEY", "YOUR-API-SECRET", true);
```

**Example response:**

```json
{
   "meta-data": {
      "code": 200,
      "message": "OK"
   },
   "token": {
      "accessToken": "upc8ecq624nfvqk9eqll8q7aaq",
      "tokenExpiration": 1400057120518,
      "refreshToken": "d0vfb1d4qtr39kea7vjvaakc5n"
   }
}
```

When your access token expires, you can generate a new one by passing in the refresh token you received from `generateTokens()`.  The same rules apply to the sandboxed vs. live environment for the third argument as they did for `generateTokens()`.

```php
// Generate a new token for the live environment
pingup::refreshToken("YOUR-REFRESH-TOKEN", "YOUR-API-SECRET");
// Generate a new token for the sandboxed environment
pingup::refreshToken("YOUR-REFRESH-TOKEN", "YOUR-API-SECRET", true);
```

**Response example:**

```json
{
   "meta-data": {
      "code": 200,
      "message": "OK"
   },
   "token": {
      "accessToken": "c214l8hi6c562dr3rjf0co6ql4",
      "tokenExpiration": 1400057098704,
      "refreshToken": "v5ahkg5hvuel9jdtvu7qtr0bk7"
   }
}
```

##Retrieving places

####getPlaces
Retrieve a list of places (businesses) available for online booking.

**Parameters**

 - *requestParams* (optional): Associative array of request parameters used to search for places.  Possible values are offset, limit, name, street, locality, region, postCode, country, latitude, longitude, radius, category, modifiedSince, factualId, and userId.  Any combination can be passed in.  See below for an explanation of all array parameters.

**Associative array parameters**

 - *offset*: Offsets the start of each page by the number specified.  Default value: 0.
 - *limit*: Number of individual places that are returned in each page.  Default value: 20.  Maximum value: 50.
 - *name*: Place/business name.
 - *street*: Address number and street name.
 - *locality*: City, town, or equivalent.
 - *region*: State, province, territory, or equivalent.
 - *postCode*: Postcode or equivalent (zip code in US).
 - *country*: Two-letter ISO country code.
 - *latitude*: Latitude in decimal degrees.
 - *longitude*: Longitude in decimal degrees.
 - *radius*: Search radius in kilometers.
 - *category*: Comma-separated list of list of category IDs.
 - *modifiedSince*: Places modified after this date (yyyy-mm-dd).
 - *factualId*: Corresponding factual ID.
 - *userId*: User ID.

**Example calls:**

```php
// Returns a list of places; no search parameters used
$pingup->getPlaces();
// Returns a list of places; some search parameters used
$pingup->getPlaces(Array("limit" => "1"));
// Another example
$pingup->getPlaces(Array("name" => "Adara Spa", "locality" => "Boston", "country" => "US"));
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK",
        "totalResults": 11715,
        "offset": 0,
        "limit": 20,
        "prev": "",
        "next": "https://api.pingup.com/v1/places?offset=20&limit=20"
    },
    "places": [{
        "id": "20231",
        "name": "Tracy Anderson Method NYC",
        "phoneNumber": "2129651408",
        "webSite": "http://www.tracyandersonmethod.com",
        "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
        "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
        "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
        "lastModified": null,
        "categories": [{
            "id": "791",
            "name": "Healthcare",
            "fullName": "Healthcare"
        }, {
            "id": "1256",
            "name": "Health and Diet Food",
            "fullName": "Retail > Food and Beverage > Health and Diet Food"
        }],
        "address": {
            "addressLine1": "24 Hubert Street",
            "addressLine2": null,
            "locality": "New York",
            "region": "NY",
            "postCode": "10013",
            "country": {
                "abbreviation": "US",
                "name": "United States"
            },
            "geo": {
                "latitude": 40.7215035,
                "longitude": -74.0101024
            },
            "timeZone": null
        },
        "bookingType": ["APPOINTMENT"],
        "factualId": null
    }]
}
```

####getPlace

Retrieve one place from its unique ID.

**Parameters**

 - *placeId*: The unique ID of the place.

**Example call:**

```php
$pingup->getPlace("20231");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "place": {
        "id": "20231",
        "name": "Tracy Anderson Method NYC",
        "phoneNumber": "2129651408",
        "webSite": "http://www.tracyandersonmethod.com",
        "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
        "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
        "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
        "lastModified": null,
        "categories": [{
            "id": "791",
            "name": "Healthcare",
            "fullName": "Healthcare"
        }, {
            "id": "1256",
            "name": "Health and Diet Food",
            "fullName": "Retail > Food and Beverage > Health and Diet Food"
        }],
        "address": {
            "addressLine1": "24 Hubert Street",
            "addressLine2": null,
            "locality": "New York",
            "region": "NY",
            "postCode": "10013",
            "country": {
                "abbreviation": "US",
                "name": "United States"
            },
            "geo": {
                "latitude": 40.7215035,
                "longitude": -74.0101024
            },
            "timeZone": null
        },
        "bookingType": ["APPOINTMENT"],
        "factualId": null
    }
}
```

###Retrieving services

####getServicesForPlaceId

Retrieve a list of services for a specific place.

**Parameters**

 - *placeId*: The unique ID of the place.

**Example call:**

```php
$pingup->getServicesForPlaceId("20231");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "services": [{
        "id": "12251-215578",
        "type": null,
        "category": "Fitness",
        "description": null,
        "name": "Sweaty Saturday Multitask Band",
        "duration": 120,
        "price": {
            "type": "EXACT",
            "amount": "0.0",
            "description": null,
            "currency": "USD"
        }
    }]
}
```

##Retrieving personnel

####getPersonnelForService

Retrieve a list of personnel for a specific service.

**Parameters**

 - *placeId*: The unique ID of the place.
 - *serviceId*: The unique ID of the service.

**Example call:**

```php
$pingup->getPersonnelForService("20231", "12251-215578");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "personnel": [{
        "id": "41316",
        "fullName": "Tracy",
        "gender": "FEMALE",
        "description": null
    }]
}
```


##Retrieving time slots

####getTimeSlotsForService

Retrieve the available time slots for a specific service.

**Parameters**

 - *placeId*: The unique ID of the place.
 - *serviceId*: The unique ID of the service.
 - *startTime*: The first possible date for the listing of time slots, inclusive (yyyy-mm-dd).
 - *endTime*: The last possible date for the listing of time slots, inclusive (yyyy-mm-dd).  The difference between startTime and endTime cannot be greater than seven days.
 - *personnelId* (optional): The unique ID of the desired personnel performing the service.  Leave blank for no preference.

**Example call:**

```php
// Get time slots for all personnel
$pingup->getTimeSlotsForService("20231", "12251-215578", "2014-08-12", "2014-08-16");
// Get time slots for specific personnel
$pingup->getTimeSlotsForService("20231", "12251-215578", "2014-08-12", "2014-08-16", "41316");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "time_slots": [{
        "personnelId": "41300",
        "duration": 120,
        "startTime": "2014-04-15T06:00",
        "timeZone": "-0400",
        "price": {
            "type": "EXACT",
            "amount": "0.0",
            "description": null,
            "currency": "USD"
        }
    }]
}
```

##Managing users

####createUser

Create a new Pingup API user.

**Parameters**

 - *firstName*: The desired first name of the user.
 - *lastName*: The desired last name of the user.
 - *phoneNumber*: The desired phone number of the user.
 - *email*: The desired email address of the user.

**Example call:**

```php
$pingup->createUser("John", "Doe", "(555) 555-5555", "john@example.com");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "user": {
        "id": "o1d9bmi8g4b4cgu79rl0bj4p2i",
        "firstName": "John",
        "lastName": "Doe",
        "phoneNumber": "(555) 555-5555",
        "email": "john@example.com"
    }
}
```

####getUser

Retrieve user information from his or her unique ID.

**Parameters**

 - *userId*: The unique ID of the user.

**Example call:**

```php
$pingup->getUser("o1d9bmi8g4b4cgu79rl0bj4p2i");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "user": {
        "id": "o1d9bmi8g4b4cgu79rl0bj4p2i",
        "firstName": "John",
        "lastName": "Doe",
        "phoneNumber": "(555) 555-5555",
        "email": "john@example.com"
    }
}
```

####editUser

Edit the information of a user.

**Parameters**

 - *userId*: The unique ID of the user.
 - *requestParams* (optional): Associative array of request parameters used to edit the user.  Possible values are firstName, lastName, phoneNumber, and email.  Any combination can be passed in.

**Example call:**

```php
$pingup->editUser("o1d9bmi8g4b4cgu79rl0bj4p2i",  Array("firstName" => "Jane", "phoneNumber" => "(777) 777-7777"));
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "user": {
        "id": "o1d9bmi8g4b4cgu79rl0bj4p2i",
        "firstName": "Jane",
        "lastName": "Doe",
        "phoneNumber": "(777) 777-7777",
        "email": "john@example.com"
    }
}
```

####deleteUser

Delete a user.

**Parameters**

 - *userId*: The unique ID of the user.

**Example call:**

```php
$pingup->deleteUser("o1d9bmi8g4b4cgu79rl0bj4p2i");
```

**Example response: **

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "user": {
        "id": "o1d9bmi8g4b4cgu79rl0bj4p2i",
        "firstName": "Jane",
        "lastName": "Doe",
        "phoneNumber": "(777) 777-7777",
        "email": "john@example.com"
    }
}
```

##Managing appointments

####createAppointment

Create a new appointment.

**Parameters**

 - *placeId*: The unique ID of the place where the appointment will be booked.
 - *serviceId*: The unique ID of the service being requested.
 - *userId*: The unique ID of the user booking the appointment.
 - *timeSlot*: Associative array of the time slot information.  See below for an explanation of how to structure the array.

**Structuring the associative array**

```json
"timeSlot": {
    "personnelId": "120475",
    "duration": "75",
    "startTime": "2014-08-12T07:30",
    "timeZone": "-0400",
    "price": {
        "type": "EXACT",
        "amount": "115.0",
        "description": null,
        "currency": "USD"
    }
}
```

As a PHP associative array this would be:

```php
$timeSlot = Array("personnelId" => "120475", "duration" => "75", "startTime" => "2014-08-12T07:30", "timeZone" => "-0400", "price" => Array("type" => "EXACT", "amount" => "115.0", "description" => "null", "currency" => "USD"));
```

**Example call:**

```php
$pingup->createAppointment("20231", "12276-843962", "o1d9bmi8g4b4cgu79rl0bj4p2i", $timeSlot);
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "appointment": {
        "id": "b1ggjdnlb6e4jsi7hvmbd09aa9c",
        "status": "SCHEDULED",
        "placeId": "20231",
        "startTime": "2014-08-11T07:30",
        "endTime": "2014-08-12T08:45",
        "timeZone": "-0400",
        "customer": {
            "email": "john@example.com",
            "firstName": "John",
            "lastName": "Doe",
            "phoneNumber": "(555) 555-5555"
        },
        "personnel": {
            "id": "120475",
            "fullName": "Maureen",
            "gender": "FEMALE",
            "description": "Licensed aesthetician\r\nAdvanced lash certified \r\n\r\n"
        },
        "service": {
            "id": "12276-843962",
            "type": "APPOINTMENT",
            "category": "Facials",
            "description": "This Galvanic treatment uses negatively charged gel and current to loosen dead skin, oils and dirt from the epidermis. Positively charged gel rich with nutrients is then forced into the deep layers of the skin with a positive current. This is a wonderful way to fresh and lighten ones appearance, giving results you can truly see, Most also see improvement in the muscle and skin tone in just one treatment! ",
            "name": "Weekly Galvanic Facial Treatment",
            "duration": 20,
            "price": {
                "type": "EXACT",
                "amount": "115.0",
                "description": null,
                "currency": "USD"
            }
        },
        "place": {
            "id": "20231",
            "name": "Tracy Anderson Method NYC",
            "phoneNumber": "2129651408",
            "webSite": "http://www.tracyandersonmethod.com",
            "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
            "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
            "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
            "lastModified": null,
            "categories": [{
                "id": "791",
                "name": "Healthcare",
                "fullName": "Healthcare"
            }, {
                "id": "1256",
                "name": "Health and Diet Food",
                "fullName": "Retail > Food and Beverage > Health and Diet Food"
            }],
            "address": {
                "addressLine1": "24 Hubert Street",
                "addressLine2": null,
                "locality": "New York",
                "region": "NY",
                "postCode": "10013",
                "country": {
                    "abbreviation": "US",
                    "name": "United States"
                },
                "geo": {
                    "latitude": 40.7215035,
                    "longitude": -74.0101024
                },
                "timeZone": null
            },
            "bookingType": ["APPOINTMENT"],
            "factualId": null
        }
    }
}
```

####getAppointments

Get a list of appointments for a specific user.

**Parameters**

 - *userId*: The unique ID of the user.
 - *requestParams* (optional): Associative array of request parameters used for search.  Possible values are offset, limit, status, dateFrom, and dateTo.  Any combination can be passed in.  See below for an explanation of all array parameters.

**Associative array parameters**

 - *offset*: Offsets the start of each page by the number specified.  Default value: 0.
 - *limit*: Number of individual appointments that are returned in each page.  Default value: 20.  Maximum value: 50.
 - *status*: The status of the appointment.  Possible values are PENDING,  SCHEDULED,  COMPLETED,  CANCELLED, or NO_SHOW.
 - *dateFrom*: Appointments scheduled after the specified date, inclusive (yyyy-mm-dd).
 - *dateTo*: Appointments scheduled before the specified date, inclusive (yyyy-mm-dd).

**Example call:**

```php
$pingup->getAppointments("o1d9bmi8g4b4cgu79rl0bj4p2i", Array("limit" => "20", "dateFrom" => "2014-08-12");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK",
        "totalResults": 24,
        "offset": 0,
        "limit": 20,
        "prev": "",
        "next": "https://api.pingup.com/v1/appointments?offset=20&limit=20&userId=o1d9bmi8g4b4cgu79rl0bj4p2i"
    },
    "appointments": [{
        "id": "b1ggjdnlb6e4jsi7hvmbd09aa9c",
        "status": "CANCELLED",
        "placeId": "20231",
        "startTime": "2014-05-20T11:00",
        "endTime": "2014-05-20T11:20",
        "timeZone": "-0500",
        "customer": {
            "customerId": "1",
            "email": "john@example.com",
            "firstName": "John",
            "lastName": "Doe",
            "phoneNumber": "(555) 555-5555"
        },
        "personnel": {
            "id": "241508",
            "fullName": "Beverly Walters",
            "gender": "MALE",
            "description": null
        },
        "service": {
            "id": "12486-211888",
            "type": "APPOINTMENT",
            "category": "Waxing",
            "description": "Duration may vary.",
            "name": "Chin Tweezing",
            "duration": 20,
            "price": {
                "type": "EXACT",
                "amount": "10.0",
                "description": null,
                "currency": "USD"
            }
        },
        "place": {
            "id": "20231",
            "name": "Tracy Anderson Method NYC",
            "phoneNumber": "2129651408",
            "webSite": "http://www.tracyandersonmethod.com",
            "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
            "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
            "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
            "lastModified": null,
            "categories": [{
                "id": "791",
                "name": "Healthcare",
                "fullName": "Healthcare"
            }, {
                "id": "1256",
                "name": "Health and Diet Food",
                "fullName": "Retail > Food and Beverage > Health and Diet Food"
            }],
            "address": {
                "addressLine1": "24 Hubert Street",
                "addressLine2": null,
                "locality": "New York",
                "region": "NY",
                "postCode": "10013",
                "country": {
                    "abbreviation": "US",
                    "name": "United States"
                },
                "geo": {
                    "latitude": 40.7215035,
                    "longitude": -74.0101024
                },
                "timeZone": null
            },
            "bookingType": ["APPOINTMENT"],
            "factualId": null
        }
    }]
}
```

####getAppointmentStatus

Get the status of an appointment from its unique ID.

**Parameters**

 - *appointmentId*: The unique ID of the appointment.
 - *userId*: The unique ID of the user who has booked the appointment.

**Example call:**

```php
$pingup->getAppointmentStatus("b30a0g7jfmosk7mu1h4sh3iq9ie", "o1d9bmi8g4b4cgu79rl0bj4p2i");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "appointment": {
        "id": "bssnb6a2n1kec7f279a0ebs7f6p",
        "status": "CANCELLED",
        "placeId": "20231",
        "startTime": "2014-04-15T06:00",
        "endTime": "2014-04-15T07:15",
        "timeZone": "-0400",
        "customer": {
            "email": "john@example.com",
            "firstName": "John",
            "lastName": "Doe",
            "phoneNumber": "(555) 555-5555"
        },
        "personnel": {
            "id": "41316",
            "fullName": "Tracy Anderson",
            "gender": "MALE",
            "description": null
        },
        "service": {
            "id": "12251-215578",
            "type": "APPOINTMENT",
            "category": "Fitness",
            "description": null,
            "name": "Tracy Anderson Connect",
            "duration": 120,
            "price": {
                "type": "EXACT",
                "amount": "0.0",
                "description": null,
                "currency": "USD"
            }
        },
        "place": {
            "id": "20231",
            "name": "Tracy Anderson Method NYC",
            "phoneNumber": "2129651408",
            "webSite": "http://www.tracyandersonmethod.com",
            "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
            "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
            "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
            "lastModified": null,
            "categories": [{
                "id": "791",
                "name": "Healthcare",
                "fullName": "Healthcare"
            }, {
                "id": "1256",
                "name": "Health and Diet Food",
                "fullName": "Retail > Food and Beverage > Health and Diet Food"
            }],
            "address": {
                "addressLine1": "24 Hubert Street",
                "addressLine2": null,
                "locality": "New York",
                "region": "NY",
                "postCode": "10013",
                "country": {
                    "abbreviation": "US",
                    "name": "United States"
                },
                "geo": {
                    "latitude": 40.7215035,
                    "longitude": -74.0101024
                },
                "timeZone": null
            },
            "bookingType": ["APPOINTMENT"],
            "factualId": null
        }
    }
}
```

####deleteAppointment

Delete an appointment.

**Parameters**

 - *appointmentId*: The unique ID of the appointment.
 - *userId*: The unique ID of the user who has booked the appointment.

**Example call:**

```php
$pingup->deleteAppointment("b30a0g7jfmosk7mu1h4sh3iq9ie", "o1d9bmi8g4b4cgu79rl0bj4p2i");
```

**Example response:**

```json
{
    "meta-data": {
        "code": 200,
        "message": "OK"
    },
    "appointment": {
        "id": "b30a0g7jfmosk7mu1h4sh3iq9ie",
        "status": "CANCELLED",
        "placeId": "20231",
        "startTime": "2014-04-15T06:00",
        "endTime": "2014-04-15T07:15",
        "timeZone": "-0400",
        "customer": {
            "customerId": null,
            "email": "john@example.com",
            "firstName": "John",
            "lastName": "Doe",
            "phoneNumber": "(555) 555-5555"
        },
        "personnel": {
            "id": "41316",
            "fullName": "Tracy Anderson",
            "gender": "FEMALE",
            "description": null
        },
        "service": {
            "id": "12251-215578",
            "type": "APPOINTMENT",
            "category": "Fitness",
            "description": null,
            "name": "Tracy Anderson Connect",
            "duration": 120,
            "price": {
                "type": "EXACT",
                "amount": "0.0",
                "description": null,
                "currency": "USD"
            }
        },
        "place": {
            "id": "20231",
            "name": "Tracy Anderson Method NYC",
            "phoneNumber": "2129651408",
            "webSite": "http://www.tracyandersonmethod.com",
            "description": "With years of research based on her former training as a dancer, Tracy has developed her very own unique approach to fitness. Her method for transforming the body is based on targeting the accessory muscles (the small muscle groups). Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. Strengthening the accessory muscles help to create a tight knit group of small muscles that actually pull in the larger muscle groups. This results in a lean figure without the extra bulk - which gives women a tiny dancer body and men a skinny ripped body.",
            "logoUrl": "http://booknow.pingup.com/l/1374767164507/3229_Tracy-Anderson-Method-NYC.png",
            "pingupUrl": "http://booknow.pingup.com/b/1374767164507",
            "lastModified": null,
            "categories": [{
                "id": "791",
                "name": "Healthcare",
                "fullName": "Healthcare"
            }, {
                "id": "1256",
                "name": "Health and Diet Food",
                "fullName": "Retail > Food and Beverage > Health and Diet Food"
            }],
            "address": {
                "addressLine1": "24 Hubert Street",
                "addressLine2": null,
                "locality": "New York",
                "region": "NY",
                "postCode": "10013",
                "country": {
                    "abbreviation": "US",
                    "name": "United States"
                },
                "geo": {
                    "latitude": 40.7215035,
                    "longitude": -74.0101024
                },
                "timeZone": null
            },
            "bookingType": ["APPOINTMENT"],
            "factualId": null
        }
    }
}
```