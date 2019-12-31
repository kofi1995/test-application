I. Create an application that displays the given data from a csv file and allows full editing functionality.
	
  A. Application Version Requirements
	1. Frontend must be in Anguar 5+
	2. Backend must be in PHP 7+
	3. You may include any npm modules needed.
	4. Frontend should be built using npm, backend should be raw php files.

  B. Backend Requirements
	1. The backend will provide an API for accepting requests from the front end.
	2. The API will allow GET requests for getting all records 
	3. The API will allow POST requests for save, update, and delete. 
	   (The impementation for these can be mocked. You do not have to write to the data file.)
	4. The API response will be JSON formatted data.
	5. The data will be read from a provided data file.

  C. Frontend Requirements
	1. Display data in a table view.
	2. Each column in the table will correspond to the field name in the data file
	3. Allow each row and field to be edited.
 	4. Allow record creation and deletion, either inline or modal.
  
  D. Data
	1. Data will be provided as csv text file
	2. The fist row of the data file will contain the field names.

  E. Tests
  	1. Any testing framework/runner will be allowed with explicit instructions. If you are looking for one to use, we use Coeception.
  	2. Any type of tests, Unit, Functional, Acceptance, or API tests.
  	3. The more test case coverage the better.
  
  F. Extras
	1. We use Docker containers, extra points if you set this to run using Docker compose cli.
	2. We use Bootstrap styles, extra points including that in the UI.
	3. Use different parent child communication styles within your application. Great Architecture is desired.
	4. Extra points for making an easy comand line install. 
	5. Make the API fully writable to the CSV file.

	