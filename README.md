# GreenIT Application Challenge

This project is the seed files needed to create a GreenIT test application.  

Objective: This test will be a fully functional web application that will display the given data from a csv file, and allow full editing capabilties of the data.

## Application Version Requirements
1. Frontend must be in Anguar 7+
2. Backend must be in PHP 7+
3. You may include any npm modules needed.
4. Frontend should be built using npm, backend should be raw php files.

## Backend Requirements
1. The backend will provide an API for accepting requests from the front end.
2. The API will allow GET requests for getting all records. 
3. The API will allow POST requests for save, update, and delete.  
(The impementation for these can be mocked. You do not have to write to the data file.)
4. The API response will be JSON formatted data.
5. The data will be read from a provided data file.

## Frontend Requirements
1. Data will be displayed in a table view.
2. Each column header in the table will correspond to a field name in the data file.
3. Allow each row and field to be edited.
4. Allow record creation and deletion, either inline or modal.
  
## Data
1. Data will be provided as csv text file.
2. The fist row of the data file will contain the field names.

## Tests
1. Any testing framework/runner will be allowed with explicit instructions. If you are looking for one to use, we use Coeception.
2. Any type of tests, Unit, Functional, Acceptance, or API tests.
3. The more test case coverage the better.
  
 ## Extras
1. We use Docker containers, extra points if you set this to run using Docker compose cli.
2. We use Bootstrap styles, extra points including that in the UI.
3. Show different parent child communication styles within your application.
4. Show or describe web server architecture used.
5. Extra points for making an easy comand line install. 
6. Make the API fully functional, and write to the CSV file.

	