# bulk-import-contact
Bulk Import XML Contacts


Notes:
Duplicates are not allowed for exact string match phone number.
Invalid format of xml not allowed.
Contacts xml file should be in same format as provided in sample xml.

Steps to run project :
1. Clone the repository
2. Setup the project
3. Create a database
4. Configure .env file (you can rename .env.example with .env and configure the database inside it)
4. Run migrations
5. Run localhost development server (php artisan serve)
6. Download the sample by clicking on download xml sample shown on homepage.
7. Choose the downloaded xml file and then click on upload and submit button.
8. For editing a contact - click on edit button and then save it by clicking on save button.
9. For deleting a contact - click on delete button.
