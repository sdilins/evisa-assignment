# evisa-assignment

# Requirements
- Docker & Docker Compose
- Postman (or any API testing tool)

# Start the app
1. Clone: `git clone https://github.com/sdilins/evisa-assignment.git`
2. Go to the repo: `cd evisa-assignment`
3. Build containers: `docker compose up -d --build`
4. Install PHP deps: `docker exec -it evisa-php composer install --no-interaction --prefer-dist`
5. Run DB migrations: `docker exec -it evisa-php php bin/console doctrine:migrations:migrate --no-interaction`

# Test the API

Postman collection at `postman/evisa-assignment.postman_collection.json` to test the basic API flows (create application, get application).

# Import into Postman (GUI)

1. Open Postman.
2. Click **Import**.
3. Drag & drop `postman/evisa-assignment.postman_collection.json` or click **Upload Files** and choose it.
4. Run the requests in the collection.

# Using the collection

**Run Create Application:**

Sends POST `localhost/api/applications`

Body (example):

    {
        "passport_number": "TST123456",
        "first_name": "John",
        "last_name": "Doe",
        "citizenship": "PL",
        "passport_expiration": "2030-12-31"
    }

Expected behavior:

HTTP Status: `200 OK`

Response body (example):

    {
        "passport_number": "TST123456",
        "status": "processing",
        "created_at": "2025-10-07T15:19:14+00:00"
    }

OR

HTTP Status: `400 Bad Request`

Response body (example):

    {
        "errors": [
            "An application with this passport_number already exists."
        ]
    }


**Run Get Application:**

Sends GET `localhost/api/applications/{{passport_number}}`

Expected behavior:

HTTP Status: `200 OK`

Response body (example):

    {
        "status": "processing"
    }

OR

HTTP Status: `404 Not Found`

Response body (example):

    {
        "error": "Application not found"
    }


# Blacklist — test data & how to validate

The project seeds a blacklist table with the following entries:

    X1234567  — Fraudulent visa application detected
    Y9876543  — Visa overstay in previous country
    Z5551111  — Forgery of passport document
    A2024056  — Suspicious travel pattern flagged
    B7654321  — Deported due to immigration violation
    C1112223  — Passport reported lost or stolen
    D9998887  — Attempted entry with invalid visa
    E5557779  — Linked to multiple failed applications
    F3334445  — Blacklist due to national security concern
    G1010101  — Inconsistent identity information

**Test with Postman:**

Add a new Postman request (or duplicate the existing Create Application request) and use a blacklisted passport to confirm the API rejects it.

Method: `POST`

URL: `localhost/api/applications`

Header: `Content-Type: application/json`

Body (example):

    {
        "passport_number": "X1234567",
        "first_name": "Bad",
        "last_name": "Actor",
        "citizenship": "PL",
        "passport_expiration": "2030-01-01"
    }

Expected behavior: 

HTTP Status: `400 Bad Request`

Response body (example):

    {
        "errors": [
            "passport_number is blacklisted."
        ]
    }