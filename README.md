# web_proj
CRUD

# Updated Some Stuff (Howard)
Added 
- order_request_items table back
- attributes (email, telephone) as unique keys in user table, made telephone NULL > NOT NULL in user table to match with customer table
- attributes (email, telephone) as foreign keys In the customer table (parent user table)
- Removed 2FA, updated Login logic
- Fixed the User creating function, updated SQL DB
- Change some words/names from original to SIT
- Fixed profile not updating as expected
- Encrypted personal info like full name, dob , email etc
- Populated user, customer, orders, order request, order items, inventory ++ tables.
- Added Role based access control for each user
- Condensed folder down into one common folder
- Added new DB for local access and updating
- 9. Added Favicon to our website
- Probably got more stuff i added, i forget already.

- To use locally, install mysql community server, go to dbcon.php and change the db username and pw to your local db login details, possibly 'root', 'root' if its different and import tpamc3.sql file inside mysql workbench and should be gtg.
- To update code to the server, make sure u push ur code to github and the transfer to remote. Do not push code if u did not pull recently in case of conflicts. 
