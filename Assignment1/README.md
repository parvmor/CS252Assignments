ASSIGNMENT 1 (due date: 00:00 3rd Sept, 2018)
============================================

# In this assignment, you will build upon the client−server architecture you developed last week a web service with the following specifications
# the server will contain an images sub−directory containing 4 images of dogs, 4 images of cats, 4 images of cars and 4 images of trucks
# the client should be able to send queries for any quantities (1...4) of any of these four categories of images to the server, i.e. have to be able to handle requeststhat look like '2 cars 3 dogs and 4 trucks'.
# the server should process the query and send back the appropriate response.
# the client should parse the response, and render the output as a table of images in a web browser.
# the response should be formatted such that it adjusts aesthetically to the number of images that have been queried
# either the client or the server has to live inside a docker container
# you can define whatever client−server communication protocols you want to in order to meet these deliverables, but you can't use any software other than UNIX commands, C, and some browser, to complete the assignment.
# each person has to push their individual contributions to the code, with proper documentation, to the team's Github repository dedicated to this project (can be hosted on any one team member's github account). You have to point the lab TA assigned to this repo by the assignment deadline.
