# Routing

API User Routes: -
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

auth/Register "Route for account registration."

user keys:
    *username = [string]
    *email = [string]
    *password = [string]

user "Shows(GET) all users."
user/{id} "Show(GET), add(PUT) or (update) user details."

user_detail keys:
    *first_name = [string]
    *last_name = [string]
    *address = [string]
    *profile_image = [string]


book "Create (POST) a book or show (GET) all books in database or (PUT) update values of a book."

book/{id} "Update (Put), Remove (DELETE) or Show a specific book."

book/genre "(GET) the genres table. or (DELETE) a genre from the table."
    *genre = name of genre to be deleted from the table

book/author "(GET) the authors table. or (DELETE) a author from the table."
    *author = name of author to be deleted from the table

book keys:
    *title = [string]
    *details = [string]
    *price = [string]
    *cover_image = [string] path to image
    *author = [string]
    *genre = [string]


rate "Create (POST) or Show (GET) all ratings."

rate/{id} "Update (PUT), Remove (DELETE), or Show a specific rate by book_id (GET)"
rate keys:
    *book_id = [integer]
    *user_id = [integer]
    *rate = [integer]
    *comment = [string]

query parameters:
    *page_size = [integer] the number of items in a page
    *page_on = [integer] the current page to be requested
