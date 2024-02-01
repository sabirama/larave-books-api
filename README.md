# Routing 

API User Routes:
auth/login - method (POST)
auth/register - method (POST)
logout - method (POST)
user/{id} - method (POST, PUT)

API Book Routes:
book - method (GET, POST)
book/{id} - method (GET, PUT, DELETE)

API Book Rating Routes:
rate - method (GET, POST)
rate/{id} - method (GET, PUT, DELETE)

# Field Keys

auth/login "Log in route."
    *username
    *password

auth/Register "Route for account registration."
    *username
    *email
    *password

user/{id} "Add or update user details."
    *first_name
    *last_name
    *address
    *image

book "Create (POST) a book or show (GET) all books in database."
    *title
    *details
    *price
    *author
    *genre

book/{id} "Update (Put), Remove (DELETE) or Show a specific book."
    *title
    *details
    *price
    *author
    *genre

rate "Create (POST) or Show (GET) all ratings."
    *book_id
    *user_id
    *rate
    *comment

rate/{id} "Update (PUT), Remove (DELETE), or Show a specific rate by book_id (GET)"
     *book_id
    *user_id
    *rate
    *comment
