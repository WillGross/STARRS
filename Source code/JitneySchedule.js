/*
 * The following two methods and 3 lines pertaining xmlHttpRequest are responsible
 * for reading in the sample TXT file that contains information on shifts.
 */
window.onload = function () {
    // Code source: https://stackoverflow.com/questions/3094997/ajax-readystate-always-1
    var xmlHttpRequest = init();
    var fileContent;
    /* Initializes the request for accessing the sample TxT file on GitHub	*/
    function init(){
        if (window.XMLHttpRequest) {
            //alert("init successful");
            return new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            alert("Your browser does not support AJAX!");
        }
    }

    /* Checks if the TXT file is ready for use	*/
    function processRequest() {
        if (xmlHttpRequest.readyState == 4) {
            if (xmlHttpRequest.status == 200 || xmlHttpRequest.status == 0) {
                fileContent = xmlHttpRequest.responseText;
            }
        }
    }
    xmlHttpRequest.open("GET", "starrs.colby.edu/driver_shift_file.txt", false);
    xmlHttpRequest.onreadystatechange = processRequest;
    xmlHttpRequest.send(null);

    /*
     * Takes in the data read from the sample TXT file, assuming that the file content
     * matches the expected format, and converts the information into a table.=
     */
    function generateTable(data) {
        // Add basic headers
        var htmlCode = '<table id="schedule" border="1">\
    	<!-- Horizontal header -->\
    	<tr>\
    		<th></th>\
    		<th>Sunday</th>\
    		<th>Monday</th>\
    		<th>Tuesday</th>\
    		<th>Wednesday</th>\
    		<th>Thursday</th>\
    		<th>Friday</th>\
    		<th>Saturday</th>\
    	</tr>';

        // After refactoring:
        // Process input data by separating rows by line break, and then separating
        // each row by ", ".
        // Also, for afternoon & evening hours, the time is emphasized in the table.
        var lines = data.split("\n");
        var TIME_BEFORE_FIRST_ROW = 7;
        var TIME_AT_NOON = 12;
        var timeOfRow = TIME_BEFORE_FIRST_ROW;
        var amIndicator = 1;
        for (i = 0; i < lines.length; i++) {
            var names = lines[i].split(", ");
            timeOfRow++;

            // Add the header for the current row
            htmlCode += '<tr align="middle" valign="middle"><th><br>';
            if (amIndicator == 0) {
                htmlCode += '<em>'+timeOfRow+':00</em>';
            } else {
                htmlCode += (timeOfRow+':00');
            }
            htmlCode += '<br><br></th>';

            // Change indicator of whether the next row will be in AM or PM
            if (timeOfRow == TIME_AT_NOON) {
                amIndicator = 1 - amIndicator;
                timeOfRow -= TIME_AT_NOON;
            }

            // Add the driver shift information for the current row
            for (j = 0; j < names.length; j++) {
                htmlCode += '<td>'+names[j]+'</td>';
            }

            htmlCode += '</tr>';
        }
        htmlCode += '</table>';
        document.body.innerHTML += htmlCode;
    }
    generateTable(fileContent);
};
