# STARRS
Shuttle Tracker and Ride Request Services for the Colby College Shuttle and Jitney

To start:
1. Go to https://starrs.colby.edu
2. The page would ask you to allow or block its access to your GPS location. If you allow, then there will be a marker marking your current location on the map.
3. You can click on the Colby banner to go to Colby home page.

To test:
--Test Suite 1:
1. Open up JitneyPage.html
2. Try out different inputs in the boxes according to the test plan. 
3. The page would pop up a warning if the inputs are not accepted, or would jump to a page of PHP script if the inputs are accepted. If the second one is the case, then you can press the Go Back key in your browser to go back to the page and start another test.

--Test Suite 2:
0. The easiest way to test the code is not by changing your system time.
1. Open up the code in MainPage.html with a text editor.
2. Find the first line in function updateEstimation() (line 287)
3. In that Date() function, add parameters like Date(2017, 11, 10, 16, 47, 30, 0) (meaning year 2017, month 11+1=12, date 10th, hours 16 i.e. 4pm, minutes 47, seconds 30, milliseconds0).
4. To test each test case, copy the year, month, date, hour, and minutes into the corresponding slots. Notice that you have to minus one from the month (so, because all test cases use December, you can keep the number 11 for the second argument), and you have to use 24-hour notation for the hour (so 16 for 4pm, and 7 for 7am, etc.). You don't have to chang ethe last two arguments (seconds and milliseconds).
5. After changing the arguments, save the file, and refresh the page of MainPage.html to see the changes below the map.

--Test Suite 3:
1. Open MainPage.html in browser.
2. Follow the steps in the test cases on the page opened in the browser.
